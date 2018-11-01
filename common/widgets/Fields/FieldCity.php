<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.09.2018
 * Time: 20:17
 */

namespace common\widgets\Fields;

use common\models\extend\FieldExtend;
use common\models\extend\GeoCityExtend;
use common\models\forms\UserFieldForm;
use dosamigos\typeahead\Bloodhound;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use yii\bootstrap\Html;
use Yii;
use yii\bootstrap\InputWidget;
use yii\helpers\Url;
use dosamigos\typeahead\TypeAhead;

class FieldCity extends InputWidget
{
    public $class = 'form-control selectpicker';
    public $style = 'btn-primary';

    public $idForm      = '#form';
    public $idContainer = '#pjaxBlock';

    public $country = 185;

    public $typeahead = true;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $model = $this->model;

        if (isset($model->city_id)) {
            $this->model[$this->attribute] = $model->city_id;
        }

        if ($this->model[$this->attribute] != null) {
            $model = GeoCityExtend::findOne($this->model[$this->attribute]);
            $cityName = $model->name_ru;
        } else {
            if (isset(Yii::$app->geoData->city) && ($this->country == Yii::$app->geoData->country)) {
                $model = GeoCityExtend::findOne(Yii::$app->geoData->city);
                $cityName = $model->name_ru;
            } else {
                $cityName = '';
            }
        }

        if ($this->typeahead) {
            $engine = new Bloodhound([
                'name' => 'countriesEngine',
                'clientOptions' => [
                    'datumTokenizer' => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
                    'queryTokenizer' => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
                    'remote' => [
                        'url' => Url::to(['/geo/set-city', 'id'=> $this->country, 'q'=>'QRY']),
                        'wildcard' => 'QRY'
                    ]
                ]
            ]);

            if ($this->country != null || $cityName != '') {
                echo TypeAhead::widget([
                    'name' => 'countriesEngine',
                    'value' => $cityName,
                    'options' => ['class' => 'form-control'],
                    'engines' => [ $engine ],
                    'clientOptions' => [
                        'highlight' => true,
                        'minLength' => 2,
                    ],
                    'clientEvents' => [
                        'typeahead:selected' => new \yii\web\JsExpression(
                            'function(obj, datum, name) {  
                        $("#city-id").val(datum.id);
                    }'
                        ),
                    ],
                    'dataSets' => [
                        [
                            'name' => 'city',
                            'displayKey' => 'city',
                            'source' => $engine->getAdapterScript(),
                            'templates' => [
                                'suggestion' => new \yii\web\JsExpression("function(data){ return '<div class=\"col-xs-12 item-container\"><div class=\"item-header\">' + data.city + '</div><div class=\"item-hint\">' + data.region + '</div></div>'; }"),
                            ],
                        ]
                    ]
                ]);
            } else {
                echo TypeAhead::widget([
                    'name' => 'countriesEngine',
                    'options' => ['class' => 'form-control', 'disabled' => 'true'],
                    'engines' => [ $engine ],
                    'clientOptions' => [
                        'highlight' => true,
                        'minLength' => 2,
                    ],
                    'clientEvents' => [
                        'typeahead:selected' => new \yii\web\JsExpression(
                            'function(obj, datum, name) {  
                        $("#city-id").val(datum.id);
                    }'
                        ),
                    ],
                    'dataSets' => [
                        [
                            'name' => 'city',
                            'displayKey' => 'city',
                            'source' => $engine->getAdapterScript(),
                            'templates' => [
                                'suggestion' => new \yii\web\JsExpression("function(data){ return '<div class=\"col-xs-12 item-container\"><div class=\"item-header\">' + data.city + '</div><div class=\"item-hint\">' + data.region + '</div></div>'; }"),
                            ],
                        ]
                    ]
                ]);
            }
            if ($this->model[$this->attribute] == null) {
                $this->model[$this->attribute] = Yii::$app->geoData->city;
            }
            echo Html::activeHiddenInput($this->model, $this->attribute, ['id' => 'city-id']);
        } else {
            $this->registerClientScript();
            echo Html::activeDropDownList($this->model, $this->attribute, GeoCityExtend::getCitiesList(), [
                'class'  => $this->class,
                'data' => [
                    'style' => $this->style,
                    'title' => Yii::t('app', 'Выберите город'),
                ],
                'onchange' => '
                    $.pjax({
                        type: "POST",
                        url: "'.Url::to(['/geo/set-selected-city']).'",
                        data: jQuery("'.$this->idForm.'").serialize(),
                        container: "'.$this->idContainer.'",
                        push: false, 
                        scrollTo: false
                })'
            ]);
        }
    }

    public function registerClientScript()
    {
        $view = $this->getView();
        BootstrapSelectAsset::register($view);
    }
}