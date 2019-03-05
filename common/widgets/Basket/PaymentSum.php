<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 04.03.2019
 * Time: 23:39
 */

namespace common\widgets\Basket;

use Yii;
use yii\base\Object;

class PaymentSum extends Object
{
    public function init()
    {
        parent::init();
    }

    public function getSums()
    {
        $parentData = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => 'basket'])
            ->one();

        if (Yii::$app->user->isGuest) {
            $items = (new \yii\db\Query())
                ->select(['value_numeric.value AS count_items', 'value_price.discount_price AS price_items', 'value_price.currency AS currency'])
                ->from('document')
                ->innerJoin('value_numeric', 'value_numeric.document_id = document.id')
                ->innerJoin('value_price', 'value_price.document_id = document.child_id')
                ->where([
                    'parent_id' => $parentData['id'],
                ])
                ->andWhere([
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent,
                ])
                ->all();
        } else {
            $items = (new \yii\db\Query())
                ->select(['value_numeric.value AS count_items', 'value_price.discount_price AS price_items', 'value_price.currency AS currency'])
                ->from('document')
                ->innerJoin('value_numeric', 'value_numeric.document_id = document.id')
                ->innerJoin('value_price', 'value_price.document_id = document.child_id')
                ->where([
                    'parent_id' => $parentData['id'],
                ])
                ->andWhere([
                    'created_by' => Yii::$app->user->id
                ])
                ->all();
        }

        $result = [];
        $i = 0;
        foreach ($items as $item) {
            if (!isset($result[$item['currency']])) {
                $result[$item['currency']] = 0;
            }
            $result[$item['currency']] = $result[$item['currency']] + ($item['count_items'] * $item['price_items']);
            $i++;
        }

        return $result;
    }
}