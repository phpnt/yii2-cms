<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.01.2019
 * Time: 18:37
 */

namespace common\widgets\ImageManager\controllers;

use Yii;
use common\models\forms\ImageForm;
use yii\web\Controller;
use yii\web\UploadedFile;

class ImageController extends Controller
{
    /**
     * Сохраняет изображения добавленные в Summernote редакторе
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionUploadSummernote($document_id = null)
    {
        $modelImageForm = new ImageForm();

        if ($modelImageForm->load(Yii::$app->request->post())) {
            $modelImageForm->images = UploadedFile::getInstances($modelImageForm, 'images');
            $urlArray = $modelImageForm->upload();
        }

        return $this->asJson(['urls' => $urlArray]);
    }
}