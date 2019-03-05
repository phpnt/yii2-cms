<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 03.03.2019
 * Time: 18:28
 */

namespace common\widgets\TemplateOfElement\fields;

use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\InputWidget;
use yii\helpers\ArrayHelper;

class FieldHidden extends InputWidget
{
    public $modelFieldForm;
    public $data_id;

    public $options = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $formName = $this->model->formName();
        $fieldID = $this->modelFieldForm->id;

        $options = [
            'id' => 'field-' . $this->data_id,
            'name' => $formName . "[elements_fields][$fieldID][0]",
        ];

        $this->options = ArrayHelper::merge($this->options, $options);

        echo Html::activeHiddenInput($this->model, $this->attribute, $this->options);
    }
}