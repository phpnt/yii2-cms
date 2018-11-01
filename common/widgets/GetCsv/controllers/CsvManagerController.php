<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 30.08.2018
 * Time: 18:08
 */

namespace common\widgets\GetCsv\controllers;

use common\models\search\DocumentSearch;
use common\widgets\GetCsv\components\CsvComponent;
use common\widgets\GetCsv\components\ZipFromFolder;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\FileHelper;
use yii\web\Controller;

class CsvManagerController extends Controller
{
    /**
     * Возвращает csv файл из модели
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionExport($with_header = true)
    {
        $models = Yii::$app->request->get('models');
        if (is_array($models)) {
            $zipName = '';
            $i = 0;
            foreach ($models as $model) {
                /* @var $searchModel DocumentSearch */
                if (!class_exists($model)) {
                    Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'danger',
                            'icon' => 'glyphicon glyphicon-ban',
                            'message' => Yii::t('app', 'Ошибка!'),
                        ]
                    );
                    return $this->goHome();
                }

                $searchModel = new $model;
                $tableName = $searchModel->tableName();

                if (($tableName == 'user' || $tableName == 'auth_assignment' || $tableName == 'user_oauth_key') && !Yii::$app->user->can('admin')) {
                    Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'danger',
                            'icon' => 'glyphicon glyphicon-ban',
                            'message' => Yii::t('app', 'У вас недостаточно прав.'),
                        ]
                    );
                    return $this->goHome();
                }

                if ($i == 0) {
                    $zipName .= $tableName;
                    $i++;
                } else {
                    $zipName .= '+'.$tableName;
                }
                /* @var $dataProvider ActiveDataProvider */
                $dataProvider = $searchModel->search([]);
                $dataProvider->pagination = false;

                $result = [];

                $y = 0;
                if ($with_header) {
                    foreach ($searchModel->getAttributes() as $key => $attribute) {
                        $result[$y][] = $key;
                    }
                    $y++;
                }

                foreach ($dataProvider->models as $data) {
                    foreach ($data->attributes as $attribute) {
                        if ($attribute) {
                            $attribute = str_replace('в?™', "'", $attribute);
                            $result[$i][] = iconv('UTF-8', 'windows-1251//IGNORE', $attribute);
                        } else {
                            $result[$i][] = '';
                        }
                    }
                    $i++;
                }

                FileHelper::createDirectory(Yii::getAlias('@backend/runtime/temp'));
                FileHelper::createDirectory(Yii::getAlias('@backend/runtime/temp/csv'));
                $file = Yii::getAlias('@backend/runtime/temp/csv/'.$tableName.'.csv');
                if (file_exists($file)) {
                    unlink($file);
                }

                $fp = fopen($file, "w"); // ("r" - считывать "w" - создавать "a" - добовлять к тексту),мы создаем файл
                fwrite($fp, CsvComponent::generateCsv($result, $delimiter = ';', $enclosure = '"'));
                fclose($fp);
            }

            FileHelper::createDirectory(Yii::getAlias('@backend/runtime/temp/zip'));

            $zipFromFolder = new ZipFromFolder();

            $zipFile = Yii::getAlias('@backend/runtime/temp/zip/db.zip');

            if (file_exists($zipFile)) {
                unlink($zipFile);
            }
            if ($zipFromFolder->getZip(Yii::getAlias('@backend/runtime/temp/csv/'), $zipFile, $delete = true)) {
                return Yii::$app->response->sendFile(Yii::getAlias('@backend/runtime/temp/zip/db.zip'), 'db('.$zipName.').zip',['mimeType'=>'application/zip']);
            } else {
                Yii::$app->session->set(
                    'message',
                    [
                        'type' => 'danger',
                        'icon' => 'glyphicon glyphicon-ban',
                        'message' => Yii::t('app', 'Включите разширение PHP "zip" или установите ZipArchive на сервере.'),
                    ]
                );
            }
        } else {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'danger',
                    'icon' => 'glyphicon glyphicon-ban',
                    'message' => Yii::t('app', 'Модели не переданы.'),
                ]
            );
        }

        return $this->goHome();
    }
}