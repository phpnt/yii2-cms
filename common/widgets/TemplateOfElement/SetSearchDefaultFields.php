<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.02.2019
 * Time: 19:23
 */

namespace common\widgets\TemplateOfElement;

use yii\base\Widget;

class SetSearchDefaultFields extends Widget
{
    public $form;
    public $model;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('search-default-fields/fields-list', [
            'widget' => $this,
        ]);
    }
}