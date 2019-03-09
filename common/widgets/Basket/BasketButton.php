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
use common\models\forms\DocumentForm;
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
        $parentData = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => 'basket'])
            ->one();

        $template_id = $parentData['template_id'];
        $parent_alias = $parentData['alias'];

        $modelDocumentForm = new DocumentForm();
        $modelDocumentForm->scenario = 'create-element';
        $modelDocumentForm->parent_id = $parentData['id'];
        $modelDocumentForm->parent_alias = $parent_alias;
        $modelDocumentForm->template_id = $template_id;
        $modelDocumentForm->field_id_prefix = $this->document_id;

        $valuePrice = (new \yii\db\Query())
            ->select(['*'])
            ->from('value_price')
            ->where([
                'type' => Constants::FIELD_TYPE_PRICE,
                'document_id' => $this->document_id,
            ])
            ->one();

        return $this->render('@frontend/views/templates/control/blocks/basket/_form-add', [
            'widget' => $this,
            'modelDocumentForm' => $modelDocumentForm,
            'valuePrice' => $valuePrice
        ]);
    }
}