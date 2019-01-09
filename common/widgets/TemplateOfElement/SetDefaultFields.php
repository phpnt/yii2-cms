<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 05.01.2019
 * Time: 16:35
 */

namespace common\widgets\TemplateOfElement;

use yii\base\Widget;

class SetDefaultFields extends Widget
{
    public $form;
    public $model;
    public $modelName;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('default-fields/fields-list', [
            'widget' => $this,
        ]);
    }
}