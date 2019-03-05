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
        $parentData = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => 'basket'])
            ->one();

        if (Yii::$app->user->isGuest) {
            $items = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'parent_id' => $parentData['id'],
                ])
                ->andWhere([
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent,
                ])
                ->count();
        } else {
            $items = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'parent_id' => $parentData['id'],
                ])
                ->andWhere([
                    'created_by' => Yii::$app->user->id
                ])
                ->count();
        }

        return $items;
    }
}