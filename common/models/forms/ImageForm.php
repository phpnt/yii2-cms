<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.01.2019
 * Time: 20:13
 */

namespace common\models\forms;

use Yii;
use yii\helpers\FileHelper;

class ImageForm extends ValueFileForm
{
    public $images = [];

    public function rules()
    {
        $items = ValueFileForm::rules();
        $items[] = [['images'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 20];

        return $items;
    }

    public function beforeValidate()
    {
        parent::beforeValidate();
        return true;
    }

    public function upload()
    {
        $urlArray = [];
        foreach ($this->images as $file) {
            $directory_1 = Yii::$app->security->generateRandomString(2);
            $directory_2 = Yii::$app->security->generateRandomString(2);
            FileHelper::createDirectory(Yii::getAlias('@frontend/web/uploads/'.$directory_1, $mode = 777));
            FileHelper::createDirectory(Yii::getAlias('@frontend/web/uploads/'.$directory_1.'/'.$directory_2, $mode = 777));
            $path = '@frontend/web/uploads/'.$directory_1.'/'.$directory_2;
            $url = '/uploads/'.$directory_1.'/'.$directory_2 . '/' . $file->name;

            $saveIs = $file->saveAs(Yii::getAlias($path . '/' . $file->name, $mode = 777));

            if ($saveIs) {
                $modelValueFileForm = new ValueFileForm();
                $modelValueFileForm->title = $this->title;
                $modelValueFileForm->name = $file->name;
                $modelValueFileForm->extension = $file->extension;
                $modelValueFileForm->size = $file->size;
                $modelValueFileForm->path = $url;
                if (!$modelValueFileForm->save()) {
                    dd($modelValueFileForm->errors);
                }
                $urlArray[] = $modelValueFileForm->path;
            } else {
                $urlArray = false;
            }
        }
        return $urlArray;
    }
}