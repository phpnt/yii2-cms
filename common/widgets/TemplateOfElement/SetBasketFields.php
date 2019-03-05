<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 03.03.2019
 * Time: 15:05
 */

namespace common\widgets\TemplateOfElement;

use yii\base\Widget;

class SetBasketFields extends Widget
{
    public $form;
    public $model;
    public $valuePrice;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('@frontend/views/templates/control/blocks/basket/fields-list', [
            'widget' => $this,
        ]);
    }
}