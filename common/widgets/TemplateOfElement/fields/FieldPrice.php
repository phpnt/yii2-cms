<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.02.2019
 * Time: 22:57
 */

namespace common\widgets\TemplateOfElement\fields;

use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\InputWidget;
use yii\helpers\ArrayHelper;

class FieldPrice extends InputWidget
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

        $priceData = $fieldsManage->getValue($this->modelFieldForm->id, $this->modelFieldForm->type, $this->model->id);

        $options = [
            'id' => 'field-' . $this->data_id,
            'name' => $formName . "[elements_fields][$fieldID][0]",
            'value' => isset($this->model->elements_fields[$this->modelFieldForm->id][0]) ? $this->model->elements_fields[$this->modelFieldForm->id][0] : $priceData['price'],
        ];

        $this->options = ArrayHelper::merge($this->options, $options);

        $this->field->label(Yii::t('app', $this->modelFieldForm->name));
        $this->field->hint('<i>' . Yii::t('app', $this->modelFieldForm->hint) . '</i>');

        if (isset($this->model->errors_fields[$this->modelFieldForm->id][0])) {
            $error = $this->model->errors_fields[$this->modelFieldForm->id][0];
            $view = $this->getView();
            $view->registerJs('addError("#group-' .  $this->data_id . '", "' . $error . '");');
        }

        echo Html::activeTextInput($this->model, $this->attribute, $this->options);

        echo '<div class="m-t-md m-b-md">';
        echo '<div class="row">';
        echo '<div class="col-sm-6">';
        $this->model->value_currency = $this->model->value_currency ? $this->model->value_currency : $priceData['currency'];
        echo Html::activeLabel($this->model, 'value_currency');
        echo Html::activeDropDownList($this->model, 'value_currency', $this->model->currencyList,
            [
                'class' => 'form-control selectpicker',
                'multiple' => false,
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ],
            ]);
        echo Html::error($this->model, 'value_currency', ['class' => 'text-danger']);
        echo '</div>';

        echo '<div class="col-sm-6">';
        $this->model->value_discount = $this->model->value_discount ? $this->model->value_discount : $priceData['discount_id'];
        echo Html::activeLabel($this->model, 'value_discount');
        echo Html::activeDropDownList($this->model, 'value_discount', $this->model->discountsAvaible,
            [
                'class' => 'form-control selectpicker',
                'multiple' => false,
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]
            ]);
        echo '</div>';

        echo '<div class="clearfix"></div>';

        echo '<div class="col-sm-3">';
        $this->model->item = $this->model->item ? $this->model->item : $priceData['item'];
        echo Html::activeLabel($this->model, 'item');
        echo Html::activeTextInput($this->model, 'item', ['class' => 'form-control']);
        echo Html::activeHint($this->model, 'item', ['hint' => '<i>' . Yii::t('app', 'Укажите количество продаваемого ассотремента.') . '</i>']);
        echo Html::error($this->model, 'item', ['class' => 'text-danger']);
        echo '</div>';

        echo '<div class="col-sm-3">';
        $this->model->item_measure = $this->model->item_measure ? $this->model->item_measure : $priceData['item_measure'];
        echo Html::activeLabel($this->model, 'item_measure');
        echo Html::activeDropDownList($this->model, 'item_measure', $this->model->measuresList,
            [
                'class' => 'form-control selectpicker',
                'multiple' => false,
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]
            ]);
        echo Html::activeHint($this->model, 'item_max', ['hint' => '<i>' . Yii::t('app', 'Мера измерения продаваемого ассотремента.') . '</i>']);
        echo Html::error($this->model, 'item_measure', ['class' => 'text-danger']);
        echo '</div>';

        echo '<div class="col-sm-3">';
        $this->model->item_max = $this->model->item_max ? $this->model->item_max : $priceData['item_max'];
        echo Html::activeLabel($this->model, 'item_max');
        echo Html::activeTextInput($this->model, 'item_max', ['class' => 'form-control']);
        echo Html::activeHint($this->model, 'item_max', ['hint' => '<i>' . Yii::t('app', 'Количество ассортемента в форме заказа. Если поле пустое, равно общему количеству.') . '</i>']);
        echo Html::error($this->model, 'item_max', ['class' => 'text-danger']);
        echo '</div>';

        echo '<div class="col-sm-3">';
        $this->model->item_store = $this->model->item_store ? $this->model->item_store : $priceData['item_store'];
        echo Html::activeLabel($this->model, 'item_store');
        echo Html::activeTextInput($this->model, 'item_store', ['class' => 'form-control']);
        echo Html::activeHint($this->model, 'item_max', ['hint' => '<i>' . Yii::t('app', 'Укажите общее количество ассортемента (на складе).') . '</i>']);
        echo Html::error($this->model, 'item_store', ['class' => 'text-danger']);
        echo '</div>';

        echo '</div>';
        echo '</div>';
    }
}