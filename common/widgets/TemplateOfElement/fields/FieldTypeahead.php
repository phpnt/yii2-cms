<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 02.02.2019
 * Time: 18:02
 */

namespace common\widgets\TemplateOfElement\fields;

use common\widgets\TypeaheadJS\assets\StyleAsset;
use common\widgets\TypeaheadJS\assets\TypeaheadAsset;
use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\InputWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\widgets\PjaxAsset;

class FieldTypeahead extends InputWidget
{
    public $modelFieldForm;
    public $data_id;

    public $options = [];

    public $setValue = true;

    public $inputNameId;
    public $changeAttribute;
    public $containerSetCookie;

    public $bloodhound = [];
    public $typeahead = [];
    public $typeaheadEvents = [];

    private $idItem;

    public function init()
    {
        parent::init();
        $fieldID = $this->modelFieldForm->id;
        $this->idItem = 'field-' . $fieldID;
    }

    public function run()
    {
        $this->registerScript();

        /* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
        $fieldsManage = Yii::$app->fieldsManage;
        $id_geo = isset($this->model->elements_fields[$this->modelFieldForm->id][0]) ? $this->model->elements_fields[$this->modelFieldForm->id][0] : $fieldsManage->getValue($this->modelFieldForm->id, $this->modelFieldForm->type, $this->model->id);
        $placeholder = false;
        $hiddenValue = false;
        if ($this->changeAttribute == 'id_geo_country') {
            $placeholder = $fieldsManage->getCountryName($id_geo);
            $hiddenValue = $id_geo ? $id_geo : $fieldsManage->getGeoId('id_geo_country');
        } elseif ($this->changeAttribute == 'id_geo_region') {
            $placeholder = $fieldsManage->getRegionName($id_geo);
            $hiddenValue = $id_geo ? $id_geo : $fieldsManage->getGeoId('id_geo_region');
        } elseif ($this->changeAttribute == 'id_geo_city') {
            $placeholder = $fieldsManage->getCityName($id_geo);
            $hiddenValue = $id_geo ? $id_geo : $fieldsManage->getGeoId('id_geo_city');
        }
        if ($this->changeAttribute == 'search-id_geo_country') {
            $placeholder = $fieldsManage->getCountrySearchName($id_geo);
            $hiddenValue = $id_geo ? $id_geo : $fieldsManage->getGeoId('id_geo_country_search');
        } elseif ($this->changeAttribute == 'search-id_geo_region') {
            $placeholder = $fieldsManage->getRegionSearchName($id_geo);
            $hiddenValue = $id_geo ? $id_geo : $fieldsManage->getGeoId('id_geo_region_search');
        } elseif ($this->changeAttribute == 'search-id_geo_city') {
            $placeholder = $fieldsManage->getCitySearchName($id_geo);
            $hiddenValue = $id_geo ? $id_geo : $fieldsManage->getGeoId('id_geo_city_search');
        }

        $formName = $this->model->formName();
        $fieldID = $this->modelFieldForm->id;

        $options = [
            'id' => $this->inputNameId,
            'value' => $this->setValue ? $placeholder : false,
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
        echo Html::activeHiddenInput($this->model, $this->changeAttribute, [
            'id' => $this->changeAttribute,
            'name' => $formName . "[elements_fields][$fieldID][0]",
            'value' => $this->setValue ? $hiddenValue : false
        ]);
        echo '<div id="' . $this->containerSetCookie . '"></div>';
    }

    public function registerScript()
    {
        $view = $this->getView();
        TypeaheadAsset::register($view);
        PjaxAsset::register($view);
        StyleAsset::register($view);

        $bloodhound = Json::encode($this->bloodhound);
        $typeahead = Json::encode($this->typeahead);

        $js = <<< JS
        var bloodhound = new Bloodhound($bloodhound);

        var typeahead = $typeahead;
        typeahead.source = bloodhound;
        
        $('#$this->inputNameId').typeahead(null, typeahead); 
JS;
        $view->registerJs($js);

        $js = [];

        foreach ($this->typeaheadEvents as $eventName => $handler) {
            $js[] = "$('#$this->inputNameId').on('$eventName', $handler);";
        }

        $view->registerJs(implode("\n", $js));
    }
}