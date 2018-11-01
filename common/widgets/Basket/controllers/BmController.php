<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 14:04
 */

namespace common\widgets\Basket\controllers;

use common\models\forms\BasketForm;
use Yii;
use yii\web\Controller;

class BmController extends Controller
{
    /**
     * Управление кнопкой "В корзину"
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionUpdate($document_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        $modelBasketFormNew = new BasketForm();
        $modelBasketFormNew->document_id = $document_id;
        $modelBasketFormNew->load(Yii::$app->request->post());

        if (Yii::$app->user->isGuest) {
            $modelBasketForm = BasketForm::find()
                ->where([
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent,
                ])
                ->one();
        } else {
            $modelBasketForm = BasketForm::find()
                ->where([
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->andWhere([
                    'user_id' => Yii::$app->user->id
                ])
                ->one();
        }

        if ($modelBasketForm) {
            $modelBasketForm->quantity = $modelBasketForm->quantity + $modelBasketFormNew->quantity;
        } else {
            $modelBasketForm = $modelBasketFormNew;
        }

        if ($modelBasketForm->save()) {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Успешно'),
                ]
            );
        }

        return $this->renderAjax('@common/widgets/MainMenu/views/_basket-product-count', [
            //'productCount' => $data
        ]);
    }
}