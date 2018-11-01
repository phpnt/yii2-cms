<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 22:05
 */

namespace common\widgets\Like\controllers;

use common\models\forms\LikeForm;
use Yii;
use yii\web\Controller;

class LikeController extends Controller
{
    /**
     * Управление кнопкой "Нравиться"
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionUpdate($document_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            // с одним IP обновляется раз в сутки
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('like')
                ->where([
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->one();
            if ($data == false) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->created_at = time();
                $modelLikeForm->document_id = $document_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->save();
            } else {
                LikeForm::deleteAll([
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
        } else {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('like')
                ->where([
                    'document_id' => $document_id,
                    'user_id' => Yii::$app->user->id
                ])
                ->one();
            if (!$data) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->created_at = time();
                $modelLikeForm->document_id = $document_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->user_id = Yii::$app->user->id;
                $modelLikeForm->save();
            } else {
                LikeForm::deleteAll([
                    'document_id' => $document_id,
                    'user_id' => Yii::$app->user->id
                ]);
            }
        }

        $likes = (new \yii\db\Query())
            ->select(['*'])
            ->from('like')
            ->where(['document_id' => $document_id])
            ->count();

        return $this->renderAjax('@common/widgets/Like/views/index', [
            'document_id' => $document_id,
            'likes' => $likes
        ]);
    }
}