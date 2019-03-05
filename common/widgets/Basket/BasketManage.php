<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 04.03.2019
 * Time: 21:57
 */

namespace common\widgets\Basket;

use common\models\Constants;
use common\models\forms\DocumentForm;
use yii\base\Widget;

class BasketManage extends Widget
{
    public $product_id;
    public $document_id;

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

        $parent_alias = $parentData['alias'];

        $modelDocumentForm = DocumentForm::findOne($this->document_id);
        $modelDocumentForm->scenario = 'update-element';
        $modelDocumentForm->parent_alias = $parent_alias;
        $modelDocumentForm->field_id_prefix = $this->document_id;

        $valuePrice = (new \yii\db\Query())
            ->select(['*'])
            ->from('value_price')
            ->where([
                'type' => Constants::FIELD_TYPE_PRICE,
                'document_id' => $this->product_id,
            ])
            ->one();

        if ($parentData['status'] == Constants::STATUS_DOC_ACTIVE) {
            return $this->render('@frontend/views/templates/control/blocks/basket/_form-add-remove', [
                'widget' => $this,
                'modelDocumentForm' => $modelDocumentForm,
                'valuePrice' => $valuePrice
            ]);
        }

        return '';
    }
}