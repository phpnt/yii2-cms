<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 15:35
 */

namespace common\widgets\Basket;

use Yii;
use yii\base\Widget;

class CountUserProducts extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if (Yii::$app->user->isGuest) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('basket')
                ->where([
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->all();
        } else {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('basket')
                ->where([
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])->orWhere(['user_id' => Yii::$app->user->id])
                ->all();
        }

        $quantity = 0;
        if ($data) {
            foreach ($data as $item) {
                $quantity = $quantity + $item['quantity'];
            }
        }

        return $quantity;
    }
}