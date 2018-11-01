<?php

namespace backend\modules\document\controllers;

use common\models\forms\DocumentForm;
use Yii;
use common\models\search\TemplateSearch;
use common\models\search\DocumentSearch;
use yii\base\InlineAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class ManageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            /* @var $action InlineAction */
                            if (!Yii::$app->user->can($action->controller->module->id . '/' . $action->controller->id . '/' . $action->id)) {
                                throw new ForbiddenHttpException(Yii::t('app', 'У вас нет доступа к этой странице'));
                            };
                            return true;
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Управление документами
     * @return string
     */
    public function actionIndex()
    {
        $modelTemplateSearch = new TemplateSearch();
        $dataProviderTemplateSearch = $modelTemplateSearch->search(Yii::$app->request->queryParams);

        $modelDocumentSearchFolder = new DocumentSearch();
        $modelDocumentSearchFolder->is_folder = 1;
        $dataProviderDocumentSearchFolders = $modelDocumentSearchFolder->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'modelTemplateSearch' => $modelTemplateSearch,
            'dataProviderTemplateSearch' => $dataProviderTemplateSearch,
            'modelDocumentSearchFolder' => $modelDocumentSearchFolder,
            'dataProviderDocumentSearchFolders' => $dataProviderDocumentSearchFolders,
        ]);
    }

    /**
     * Для отображения структуры документов в шапке
     * @return mixed
     */
    public function actionGetRoot($id = null)
    {
        $manyDocumentForm = DocumentForm::findAll(
            ['parent_id' => null]
        );

        $data = [];

        $i = 0;
        foreach ($manyDocumentForm as $modelDocumentForm) {
            $data[$i]['id'] = $modelDocumentForm->id;
            $data[$i]['parent'] = '#';
            $data[$i]['text'] = Yii::t('app', $modelDocumentForm->name) . ' ' . $modelDocumentForm->statusItem;;
            $data[$i]['icon'] = 'fa fa-folder';
            $data[$i]['children'] = true;
            $i++;
        }

        return $this->asJson($data);
    }

    /**
     * Для отображения структуры документов в шапке
     * @return mixed
     */
    public function actionGetChilds($id)
    {
        $manyDocumentForm = DocumentForm::findAll([
            'parent_id' => $id
        ]);

        $data = [];

        $i = 0;
        foreach ($manyDocumentForm as $modelDocumentForm) {
            if ($modelDocumentForm->is_folder) {
                $data[$i]['id'] = $modelDocumentForm->id;
                $data[$i]['parent'] = $id;
                $data[$i]['text'] = Yii::t('app', $modelDocumentForm->name) . ' ' . $modelDocumentForm->statusItem;;
                $data[$i]['icon'] = 'fa fa-folder';
                $data[$i]['children'] = true;
            } else {
                $data[$i]['id'] = $modelDocumentForm->id;
                $data[$i]['parent'] = $id;
                $data[$i]['text'] = Yii::t('app', $modelDocumentForm->name);
                $data[$i]['icon'] = 'fa fa-file';
            }
            $i++;
        }

        // сортировка, папки вверху
        $folders = [];
        $elements = [];
        foreach ($data as $item) {
            if (isset($item['children'])) {
                $folders[] = $item;
            } else {
                $elements[] = $item;
            }
        }

        foreach ($elements as $element) {
            $folders[] = $element;
        }

        $data = $folders;
        return $this->asJson($data);
    }
}
