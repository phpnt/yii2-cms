<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 08.01.2019
 * Time: 7:37
 */

namespace common\widgets\TemplateOfElement;

use yii\base\Widget;

class SetGeoFields extends Widget
{
    public $form;
    public $modelGeoTemplateForm;


    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('geo-fields/fields-list', [
            'widget' => $this,
        ]);
    }
}