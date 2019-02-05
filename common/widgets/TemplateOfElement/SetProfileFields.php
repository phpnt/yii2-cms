<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 04.02.2019
 * Time: 13:57
 */

namespace common\widgets\TemplateOfElement;

use yii\base\Widget;

class SetProfileFields extends Widget
{
    public $form;
    public $model;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('@frontend/views/templates/profile/fields-list', [
            'widget' => $this,
        ]);
    }
}