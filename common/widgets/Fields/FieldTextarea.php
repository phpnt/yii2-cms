<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.09.2018
 * Time: 17:32
 */

namespace common\widgets\Fields;

use yii\bootstrap\Html;
use yii\bootstrap\InputWidget;

class FieldTextarea extends InputWidget
{
    public $name;
    public $options = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        echo Html::activeTextarea($this->model, $this->attribute, $this->options);
    }
}