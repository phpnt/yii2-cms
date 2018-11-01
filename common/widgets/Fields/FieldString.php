<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.09.2018
 * Time: 17:24
 */

namespace common\widgets\Fields;

use yii\bootstrap\Html;
use yii\bootstrap\InputWidget;

class FieldString extends InputWidget
{
    public $name;
    public $options = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        echo Html::activeTextInput($this->model, $this->attribute, $this->options);
    }
}