<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.01.2019
 * Time: 6:27
 */

use common\models\Constants;
use common\widgets\TemplateOfElement\fields\FieldText;
use common\widgets\TemplateOfElement\fields\FieldTextRangeFrom;
use common\widgets\TemplateOfElement\fields\FieldTextRangeTo;
use common\widgets\TemplateOfElement\fields\FieldCheckbox;
use common\widgets\TemplateOfElement\fields\FieldRadio;
use common\widgets\TemplateOfElement\fields\FieldDropdown;
use common\widgets\TemplateOfElement\fields\FieldDatepickerFrom;
use common\widgets\TemplateOfElement\fields\FieldDatepickerTo;
use common\widgets\TemplateOfElement\fields\FieldTypeahead;

/* @var $this \yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $widget \common\widgets\TemplateOfElement\SetDefaultFields */
/* @var $model \common\models\forms\DocumentForm */
/* @var $modelName string */
$form = $widget->form;
$model = $widget->model;
?>
<?php foreach ($model->template->fields as $modelFieldForm): ?>
    <?php /* @var $modelFieldForm \common\models\forms\FieldForm */ ?>
    <?php if ($modelFieldForm->use_filter): ?>
        <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_INT ||
            $modelFieldForm->type == Constants::FIELD_TYPE_DISCOUNT ||
            $modelFieldForm->type == Constants::FIELD_TYPE_FLOAT ||
            $modelFieldForm->type == Constants::FIELD_TYPE_PRICE): ?>
            <div class="col-xs-12">
                <label class="control-label"><?= Yii::t('app', $modelFieldForm->name) ?></label>
            </div>
            <div class="col-xs-6">
                <?= $form->field($model, 'value_number', [
                    'options' => [
                        'id' => 'group-' . $modelFieldForm->id . '-0'
                    ]
                ])->widget(FieldTextRangeFrom::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'options' => [
                        'class' => 'form-control input-xs',
                    ],
                ])->label(Yii::t('app', 'От')) ?>
            </div>
            <div class="col-xs-6">
                <?= $form->field($model, 'value_number', [
                    'options' => [
                        'id' => 'group-' . $modelFieldForm->id . '-1'
                    ]
                ])->widget(FieldTextRangeTo::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'options' => [
                        'class' => 'form-control input-xs',
                    ],
                ])->label(Yii::t('app', 'До')) ?>
            </div>
            <?php if ($modelFieldForm->hint): ?>
                <div class="col-xs-12">
                    <p class="help-block"><i><?= Yii::t('app', $modelFieldForm->hint) ?></i></p>
                </div>
            <?php endif; ?>
        <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_STRING): ?>
            <div class="col-md-12">
                <?= $form->field($model, 'value_string', [
                    'options' => [
                        'id' => 'group-' . $modelFieldForm->id,
                    ]
                ])->widget(FieldText::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'options' => [
                        'class' => 'form-control input-xs',
                    ],
                ]) ?>
            </div>
        <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_CHECKBOX): ?>
            <div class="col-md-12">
                <?= $form->field($model, 'value_int', [
                    'options' => [
                        'id' => 'group-' . $modelFieldForm->id,

                    ],
                    'template' => "{label}\n{input}\n{hint}\n{error}"
                ])->widget(FieldCheckbox::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'type'  => FieldCheckbox::TYPE_CHECBOX,
                    'style'  => FieldCheckbox::STYLE_FLAT,
                    'color'  => 'blue',
                    'items' => $modelFieldForm->list,
                    'options' => [
                        //'class' => 'hidden-input',
                    ],
                ]) ?>
            </div>
        <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_RADIO): ?>
            <div class="col-md-12">
                <?= $form->field($model, 'value_int', [
                    'options' => [
                        'id' => 'group-' . $modelFieldForm->id,

                    ],
                    'template' => "{label}\n{input}\n{hint}\n{error}"
                ])->widget(FieldRadio::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'type'  => FieldRadio::TYPE_RADIO_LIST,
                    'style'  => FieldRadio::STYLE_FLAT,
                    'color'  => 'blue',
                    'items' => $modelFieldForm->list,
                    'options' => [
                        //'class' => 'hidden-input',
                    ],
                ]) ?>
            </div>
        <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_LIST): ?>
            <div class="col-md-12">
                <?= $form->field($model, 'value_int', [
                    'options' => [
                        'id' => 'group-' . $modelFieldForm->id
                    ]
                ])->widget(FieldDropdown::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'options' => [
                        'class' => 'form-control',
                        'multiple' => false,
                        'data' => [
                            'style' => 'btn-default btn-sm',
                            'live-search' => 'false',
                            'title' => '---'
                        ]
                    ],
                ]) ?>
            </div>
        <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_LIST_MULTY): ?>
            <div class="col-md-12">
                <?= $form->field($model, 'value_int', [
                    'options' => [
                        'id' => 'group-' . $modelFieldForm->id
                    ]
                ])->widget(FieldDropdown::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'options' => [
                        'class' => 'form-control',
                        'multiple' => 'true',
                        'data' => [
                            'style' => 'btn-default btn-sm',
                            'live-search' => 'false',
                            'title' => '---'
                        ]
                    ],
                ]) ?>
            </div>
        <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_DATE): ?>
            <div class="col-md-12">
                <label class="control-label"><?= Yii::t('app', $modelFieldForm->name) ?></label>
            </div>
            <div class="col-xs-6">
                <?= $form->field($model, 'value_int', [
                    'options' => [
                        'id' => 'group-' . $modelFieldForm->id . '-0'
                    ]
                ])->widget(FieldDatepickerFrom::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'widgetContainerId' => 'group-' . $modelFieldForm->id . '-0',
                    'options' => [
                        'class' => 'form-control input-xs',
                    ],
                    'calendarIcon' => "<span class='input-group-addon addon-xs'><i class='fas fa-calendar'></i></span>",
                    'datapickerOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                        'language'  => Yii::$app->language,
                    ],
                ])->label(Yii::t('app', 'От')) ?>
            </div>
            <div class="col-xs-6">
                <?= $form->field($model, 'value_int', [
                    'options' => [
                        'id' => 'group-' . $modelFieldForm->id . '-1'
                    ]
                ])->widget(FieldDatepickerTo::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'widgetContainerId' => 'group-' . $modelFieldForm->id . '-1',
                    'options' => [
                        'class' => 'form-control input-xs',
                    ],
                    'calendarIcon' => "<span class='input-group-addon addon-xs'><i class='fas fa-calendar'></i></span>",
                    'datapickerOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                        'language'  => Yii::$app->language,
                    ],
                ])->label(Yii::t('app', 'До')) ?>
            </div>
            <?php if ($modelFieldForm->hint): ?>
                <div class="col-xs-12">
                    <p class="help-block"><i><?= Yii::t('app', $modelFieldForm->hint) ?></i></p>
                </div>
            <?php endif; ?>
        <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_COUNTRY): ?>
            <div class="col-md-12">
                <?= $form->field($model, 'value_string', [
                    'options' => [
                        'id' => 'search-group-' . $modelFieldForm->id
                    ]
                ])->widget(FieldTypeahead::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'options' => [
                        'class' => 'form-control input-xs',
                    ],
                    'inputNameId' => 'search-name_geo_country',
                    'changeAttribute' => 'search-id_geo_country',
                    'containerSetCookie' => 'search-container-id_geo_country',
                    'bloodhound' => [
                        'datumTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
                        'queryTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
                        'remote'            => [
                            'url'           => '/geo-manage/get-search-country?query=%QUERY&lang='.Yii::$app->language,
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
                    $("#search-name_geo_region").val("");
                    $("#search-id_geo_region").val("");
                    $("#search-name_geo_city").val("");
                    $("#search-id_geo_city").val("");
                    $("#search-id_geo_country").val(datum.id);
                    $.pjax({
                        type: "GET", 
                        url: "/geo-manage/set-search-cookie?name=id_geo_country_search&value=" + datum.id,
                        container: "#search-container-id_geo_country",
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
                <?= $form->field($model, 'value_string', [
                    'options' => [
                        'id' => 'search-group-' . $modelFieldForm->id
                    ]
                ])->widget(FieldTypeahead::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'options' => [
                        'class' => 'form-control input-xs',
                    ],
                    'inputNameId' => 'search-name_geo_region',
                    'changeAttribute' => 'search-id_geo_region',
                    'containerSetCookie' => 'search-container-id_geo_region',
                    'bloodhound' => [
                        'datumTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
                        'queryTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
                        'remote'            => [
                            'url'           => '/geo-manage/get-search-region?query=%QUERY&lang='.Yii::$app->language,
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
                    $("#search-name_geo_city").val("");
                    $("#search-id_geo_city").val("");
                    $("#search-id_geo_region").val(datum.id);
                    $.pjax({
                        type: "GET", 
                        url: "/geo-manage/set-search-cookie?name=id_geo_region_search&value=" + datum.id,
                        container: "#search-container-id_geo_region",
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
                <?= $form->field($model, 'value_string', [
                    'options' => [
                        'id' => 'search-group-' . $modelFieldForm->id
                    ]
                ])->widget(FieldTypeahead::class, [
                    'modelFieldForm' => $modelFieldForm,
                    'options' => [
                        'class' => 'form-control input-xs',
                    ],
                    'inputNameId' => 'search-name_geo_city',
                    'changeAttribute' => 'search-id_geo_city',
                    'containerSetCookie' => 'search-container-id_geo_city',
                    'bloodhound' => [
                        'datumTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
                        'queryTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
                        'remote'            => [
                            'url'           => '/geo-manage/get-search-city?query=%QUERY&lang='.Yii::$app->language,
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
                        $("#search-id_geo_city").val(datum.id);
                        $.pjax({
                            type: "GET", 
                            url: "/geo-manage/set-search-cookie?name=id_geo_city_search&value=" + datum.id,
                            container: "#search-container-id_geo_city",
                            push: false,
                            timeout: 20000,
                            scrollTo: false
                        });
            }'),
                    ],
                ]) ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>
