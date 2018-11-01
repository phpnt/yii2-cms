<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 13:54
 */

namespace common\widgets\Basket;

use common\models\Constants;
use common\models\forms\BasketForm;
use yii\base\Widget;

class BasketButton extends Widget
{
    public $document_id;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $data = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'basket',
            ])
            ->one();

        if ($data['status'] == Constants::STATUS_DOC_ACTIVE) {
            $modelBasketForm = new BasketForm();
            $modelBasketForm->document_id = $this->document_id;

            return $this->render('index', [
                'modelBasketForm' => $modelBasketForm,
            ]);
        }
    }
}