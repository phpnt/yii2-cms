<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 03.03.2019
 * Time: 15:33
 */

namespace common\widgets\TemplateOfElement\fields;

use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\InputWidget;
use yii\helpers\ArrayHelper;

class FieldNumber extends InputWidget
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
        /* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
        $fieldsManage = Yii::$app->fieldsManage;

        $formName = $this->model->formName();
        $fieldID = $this->modelFieldForm->id;

        $options = [
            'id' => 'field-' . $this->data_id,
            'type' => 'number',
            'name' => $formName . "[elements_fields][$fieldID][0]",
            'value' => isset($this->model->elements_fields[$this->modelFieldForm->id][0]) ? $this->model->elements_fields[$this->modelFieldForm->id][0] : $fieldsManage->getValue($this->modelFieldForm->id, $this->modelFieldForm->type, $this->model->id),
        ];

        $this->options = ArrayHelper::merge($this->options, $options);
        $this->field->label(Yii::t('app', $this->modelFieldForm->name));
        $this->field->hint('<i>' . Yii::t('app', $this->modelFieldForm->hint) . '</i>');

        if (isset($this->model->errors_fields[$this->modelFieldForm->id][0])) {
            $error = $this->model->errors_fields[$this->modelFieldForm->id][0];
            $view = $this->getView();
            $view->registerJs('addError("#group-' .  $this->data_id . '", "' . $error . '");');
        }

        echo '<div class="input-group">';
        echo '<span class="input-group-btn"><button id="minus-' . $this->data_id . '" class="btn btn-danger" type="button">-</button></span>';
        echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        echo '<span class="input-group-btn"><button id="plus-' . $this->data_id . '" class="btn btn-success" type="button">+</button></span>';
        echo '</div>';

        $view = $this->getView();
        $min = $this->options['min'];
        $max = $this->options['max'];
        $js = <<<JS
            $('#minus-$this->data_id').click(function(){
                if ($("#field-$this->data_id").val() > $min) {
                    $("#field-$this->data_id").val(parseInt($("#field-$this->data_id").val())-1);    
                }
            });
            $('#plus-$this->data_id').click(function(){
                if ($("#field-$this->data_id").val() < $max) {
                    $("#field-$this->data_id").val(parseInt($("#field-$this->data_id").val())+1);
                }
            }); 
JS;
        $view->registerJs($js);
    }
}