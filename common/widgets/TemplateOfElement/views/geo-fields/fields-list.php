<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.01.2019
 * Time: 6:27
 */

use common\models\Constants;
use common\widgets\TemplateOfElement\fields\FieldTypeahead;

/* @var $this \yii\web\View */
/* @var $widget \common\widgets\TemplateOfElement\SetGeoFields */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelGeoTemplateForm \common\widgets\TemplateOfElement\forms\GeoTemplateForm */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

$form = $widget->form;
$modelGeoTemplateForm = $widget->modelGeoTemplateForm;
?>
<?php foreach ($modelGeoTemplateForm->fields as $modelFieldForm): ?>

    <?php /* @var $modelFieldForm \common\models\forms\FieldForm */ ?>
    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_COUNTRY): ?>
        <div class="col-md-12">
            <?= $form->field($modelGeoTemplateForm, 'value_string', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldTypeahead::class, [
                'modelFieldForm' => $modelFieldForm,
                'data_id' => $modelFieldForm->id,
                'options' => [
                    'class' => 'form-control',
                ],
                'inputNameId' => 'name_geo_country',
                'changeAttribute' => 'id_geo_country',
                'containerSetCookie' => 'container-id_geo_country',
                'bloodhound' => [
                    'datumTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
                    'queryTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
                    'remote'            => [
                        'url'           => '/geo-manage/get-country?query=%QUERY&lang='.Yii::$app->language,
                        'wildcard'      => '%QUERY'
                    ]
                ],
                'typeahead' => [
                    'name' => 'name',
                    'display' => 'name',
                ],
                'typeaheadEvents' => [
                    'typeahead:selected' => new \yii\web\JsExpression(
                        'function(obj, datum, name) {
                    $("#name_geo_region").val("");
                    $("#id_geo_region").val("");
                    $("#name_geo_city").val("");
                    $("#id_geo_city").val("");
                    $("#id_geo_country").val(datum.id);
                    $.pjax({
                        type: "GET", 
                        url: "/geo-manage/set-cookie?name=id_geo_country&value=" + datum.id,
                        container: "#container-id_geo_country",
                        push: false,
                        timeout: 20000,
                        scrollTo: false
                    });
            }'),
                ],
            ]) ?>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_REGION): ?>
        <div class="col-md-12">
            <?= $form->field($modelGeoTemplateForm, 'value_string', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldTypeahead::class, [
                'modelFieldForm' => $modelFieldForm,
                'data_id' => $modelFieldForm->id,
                'options' => [
                    'class' => 'form-control',
                ],
                'inputNameId' => 'name_geo_region',
                'changeAttribute' => 'id_geo_region',
                'containerSetCookie' => 'container-id_geo_region',
                'bloodhound' => [
                    'datumTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
                    'queryTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
                    'remote'            => [
                        'url'           => '/geo-manage/get-region?query=%QUERY&lang='.Yii::$app->language,
                        'wildcard'      => '%QUERY'
                    ]
                ],
                'typeahead' => [
                    'name' => 'name',
                    'display' => 'name',
                ],
                'typeaheadEvents' => [
                    'typeahead:selected' => new \yii\web\JsExpression(
                        'function(obj, datum, name) {
                    $("#name_geo_city").val("");
                    $("#id_geo_city").val("");
                    $("#id_geo_region").val(datum.id);
                    $.pjax({
                        type: "GET", 
                        url: "/geo-manage/set-cookie?name=id_geo_region&value=" + datum.id,
                        container: "#container-id_geo_region",
                        push: false,
                        timeout: 20000,
                        scrollTo: false
                    });
            }'),
                ],
            ]) ?>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_CITY): ?>
        <div class="col-md-12">
            <?= $form->field($modelGeoTemplateForm, 'value_string', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldTypeahead::class, [
                'modelFieldForm' => $modelFieldForm,
                'data_id' => $modelFieldForm->id,
                'options' => [
                    'class' => 'form-control',
                ],
                'inputNameId' => 'name_geo_city',
                'changeAttribute' => 'id_geo_city',
                'containerSetCookie' => 'container-id_geo_city',
                'bloodhound' => [
                    'datumTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
                    'queryTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
                    'remote'            => [
                        'url'           => '/geo-manage/get-city?query=%QUERY&lang='.Yii::$app->language,
                        'wildcard'      => '%QUERY'
                    ]
                ],
                'typeahead' => [
                    'name' => 'name',
                    'display' => 'name',
                ],
                'typeaheadEvents' => [
                    'typeahead:selected' => new \yii\web\JsExpression(
                        'function(obj, datum, name) {
                        $("#id_geo_city").val(datum.id);
                        $.pjax({
                            type: "GET", 
                            url: "/geo-manage/set-cookie?name=id_geo_city&value=" + datum.id,
                            container: "#container-id_geo_city",
                            push: false,
                            timeout: 20000,
                            scrollTo: false
                        });
            }'),
                ],
            ]) ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
