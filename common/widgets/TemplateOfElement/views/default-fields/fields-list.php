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
use common\widgets\TemplateOfElement\fields\FieldTextarea;
use common\widgets\TemplateOfElement\fields\FieldTextRangeFrom;
use common\widgets\TemplateOfElement\fields\FieldTextRangeTo;
use common\widgets\TemplateOfElement\fields\FieldCheckbox;
use common\widgets\TemplateOfElement\fields\FieldRadio;
use common\widgets\TemplateOfElement\fields\FieldDropdown;
use common\widgets\TemplateOfElement\fields\FieldDatepicker;
use common\widgets\TemplateOfElement\fields\FieldDatepickerFrom;
use common\widgets\TemplateOfElement\fields\FieldDatepickerTo;
use common\widgets\TemplateOfElement\fields\FieldTypeahead;
use common\widgets\TemplateOfElement\fields\FieldFile;

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
    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_INT): ?>
        <div class="col-md-12">
            <?= $form->field($model, 'value_int', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldText::class, [
                'modelFieldForm' => $modelFieldForm,
                'options' => [
                    'class' => 'form-control',
                ],
            ]) ?>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_INT_RANGE): ?>
        <div class="col-md-12">
            <hr>
            <label class="control-label"><?= $modelFieldForm->name ?></label>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'value_int', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id . '-0'
                ]
            ])->widget(FieldTextRangeFrom::class, [
                'modelFieldForm' => $modelFieldForm,
                'options' => [
                    'class' => 'form-control',
                ],
            ])->label(Yii::t('app', 'От')) ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'value_int', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id . '-1'
                ]
            ])->widget(FieldTextRangeTo::class, [
                'modelFieldForm' => $modelFieldForm,
                'options' => [
                    'class' => 'form-control',
                ],
            ])->label(Yii::t('app', 'До')) ?>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FLOAT ||
        $modelFieldForm->type == Constants::FIELD_TYPE_PRICE): ?>
        <div class="col-md-12">
            <?= $form->field($model, 'value_number', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldText::class, [
                'modelFieldForm' => $modelFieldForm,
                'options' => [
                    'class' => 'form-control',
                ],
            ]) ?>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FLOAT_RANGE): ?>
        <div class="col-md-12">
            <hr>
            <label class="control-label"><?= $modelFieldForm->name ?></label>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'value_number', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id . '-0'
                ]
            ])->widget(FieldTextRangeFrom::class, [
                'modelFieldForm' => $modelFieldForm,
                'options' => [
                    'class' => 'form-control',
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
                    'class' => 'form-control',
                ],
            ])->label(Yii::t('app', 'До')) ?>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_STRING ||
        $modelFieldForm->type == Constants::FIELD_TYPE_ADDRESS ||
        $modelFieldForm->type == Constants::FIELD_TYPE_EMAIL ||
        $modelFieldForm->type == Constants::FIELD_TYPE_URL ||
        $modelFieldForm->type == Constants::FIELD_TYPE_SOCIAL ||
        $modelFieldForm->type == Constants::FIELD_TYPE_YOUTUBE): ?>
        <div class="col-md-12">
            <?= $form->field($model, 'value_string', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldText::class, [
                'modelFieldForm' => $modelFieldForm,
                'options' => [
                    'class' => 'form-control',
                ],
            ]) ?>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_TEXT): ?>
        <div class="col-md-12">
            <?= $form->field($model, 'value_string', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldTextarea::class, [
                'modelFieldForm' => $modelFieldForm,
                'options' => [
                    'class' => 'form-control',
                    'rows' => 4
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
                        'style' => 'btn-default',
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
                        'style' => 'btn-default',
                        'live-search' => 'false',
                        'title' => '---'
                    ]
                ],
            ]) ?>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_DATE): ?>
        <div class="col-md-12">
            <?= $form->field($model, 'value_int', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id,

                ]
            ])->widget(FieldDatepicker::class, [
                'modelFieldForm' => $modelFieldForm,
                'widgetContainerId' => 'group-' . $modelFieldForm->id,
                'options' => [
                    'class' => 'form-control',
                ],
                'datapickerOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy',
                    'language'  => Yii::$app->language,
                ],
            ]) ?>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_DATE_RANGE): ?>
        <div class="col-md-12">
            <hr>
            <label class="control-label"><?= $modelFieldForm->name ?></label>
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
                    'class' => 'form-control',
                ],
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
                    'class' => 'form-control',
                ],
                'datapickerOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy',
                    'language'  => Yii::$app->language,
                ],
            ])->label(Yii::t('app', 'От')) ?>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_COUNTRY): ?>
        <div class="col-md-12">
            <?= $form->field($model, 'value_string', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldTypeahead::class, [
                'modelFieldForm' => $modelFieldForm,
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
            <?= $form->field($model, 'value_string', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldTypeahead::class, [
                'modelFieldForm' => $modelFieldForm,
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
            <?= $form->field($model, 'value_string', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldTypeahead::class, [
                'modelFieldForm' => $modelFieldForm,
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
                        $("#name_geo_city").val("");
                        $("#id_geo_city").val("");
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
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FILE): ?>
        <div class="col-md-12">
            <?= $form->field($model, 'file', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldFile::class, [
                'modelFieldForm' => $modelFieldForm,
                'options' => [
                    'class' => 'form-control',
                ],
            ]) ?>
            <?php /* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */ ?>
            <?php $fieldsManage = Yii::$app->fieldsManage; ?>
            <?php $modelValueFileForm = $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $model->id); ?>
            <?php if ($modelValueFileForm): ?>
                <?= $this->render('_file', [
                    'modelValueFileForm' => $modelValueFileForm
                ]) ?>
            <?php endif; ?>
            <hr>
        </div>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FEW_FILES
    ): ?>
        <div class="col-md-12">
            <?= $form->field($model, 'few_files[]', [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id
                ]
            ])->widget(FieldFile::class, [
                'modelFieldForm' => $modelFieldForm,
                'options' => [
                    'class' => 'form-control',
                    'multiple' => true
                ],
            ]) ?>
            <?php /* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */ ?>
            <?php $fieldsManage = Yii::$app->fieldsManage; ?>
            <?php $manyValueFileForm = $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $model->id); ?>
            <?php if ($manyValueFileForm): ?>
                <?= $this->render('_files', [
                    'manyValueFileForm' => $manyValueFileForm
                ]) ?>
            <?php endif; ?>
            <hr>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
