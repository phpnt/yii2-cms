<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 29.07.2018
 * Time: 15:55
 */

namespace common\widgets\TypeaheadJS;

use yii\bootstrap\InputWidget;
use common\widgets\TypeaheadJS\assets\StyleAsset;
use common\widgets\TypeaheadJS\assets\TypeaheadAsset;
use yii\bootstrap\Html;
use yii\helpers\Json;

class TypeaheadField extends  InputWidget
{
    public $changeAttribute;
    public $name;
    public $hiddenValue;

    public $bloodhound = [];

    public $typeahead = [];
    public $typeaheadEvents = [];

    public function init()
    {
        parent::init();
        $view = $this->getView();
        TypeaheadAsset::register($view);
        StyleAsset::register($view);
    }

    public function run()
    {
        $this->registerClientScript();

        echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        echo Html::activeHiddenInput($this->model, $this->changeAttribute, [
            'id' => $this->changeAttribute,
            'name' => $this->name,
            'value' => $this->hiddenValue
        ]);
    }

    private function registerClientScript()
    {
        $view = $this->getView();

        $bloodhound = Json::encode($this->bloodhound);
        $typeahead = Json::encode($this->typeahead);

        $id = $this->options['id'];

        $js = <<< JS
        var bloodhound = new Bloodhound($bloodhound);

        var typeahead = $typeahead;
        typeahead.source = bloodhound;

        $('#$id').typeahead(null, typeahead); 
JS;
        $view->registerJs($js);

        $js = [];

        foreach ($this->typeaheadEvents as $eventName => $handler) {
            $js[] = "$('#$id').on('$eventName', $handler);";
        }

        $view->registerJs(implode("\n", $js));
    }
}