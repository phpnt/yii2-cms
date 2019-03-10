<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 09.03.2019
 * Time: 9:53
 */

namespace common\widgets\TemplateOfElement;

use yii\base\Widget;

class SetCommentFields extends Widget
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
        return $this->render('@frontend/views/templates/control/blocks/comment/fields-list', [
            'widget' => $this,
        ]);
    }
}