<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.08.2018
 * Time: 13:55
 */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;
use backend\assets\TranslateAsset;
use mihaildev\ckeditor\CKEditor;
use common\models\Constants;
use phpnt\ICheck\ICheck;
use phpnt\bootstrapSelect\BootstrapSelectAsset;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<div id="elements-form-block">
    <?php BootstrapSelectAsset::register($this) ?>
    <?php TranslateAsset::register($this) ?>
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => $modelDocumentForm->isNewRecord ? Url::to(['element-manage/create-element', 'id_folder' => $modelDocumentForm->parent_id]) : Url::to(['element-manage/update-element', 'id_document' => $modelDocumentForm->id, 'id_folder' => $modelDocumentForm->parent_id]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="col-md-3">
        <?= $form->field($modelDocumentForm, 'name')
            ->textInput(['placeholder' => $modelDocumentForm->getAttributeLabel('name')]) ?>
    </div>

    <div class="col-md-5">
        <?= $form->field($modelDocumentForm, 'title', [
            'template' =>  '{label}
                            <div id="title-btn" class="input-group">
                                {input}
                                <span class="input-group-btn">
                                    <a class="btn btn-default repeat-name" href="#">'.Yii::t('app', 'Повторить название').'</a>
                                </span>
                            </div>
                            {hint}
                            {error}'])
            ->textInput(['placeholder' => $modelDocumentForm->getAttributeLabel('title')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($modelDocumentForm, 'alias', [
            'template' =>  '{label}
                            <div id="title-btn" class="input-group">
                                {input}
                                <span class="input-group-btn">
                                    '.ButtonDropdown::widget([
                    'label' => Yii::t('app', 'Сформировать'),
                    'dropdown' => [
                        'items' => [
                            ['label' => Yii::t('app', 'Из названия'), 'url' => '#', 'options' => ['class'=>'translate-name']],
                            ['label' => Yii::t('app', 'Из заголовка'), 'url' => '#', 'options' => ['class'=>'translate-title']],
                        ],
                    ],
                    'options' => ['class'=>'btn-default']]).'
                                </span>
                            </div>
                            {hint}
                            {error}'])
            ->textInput(['placeholder' => $modelDocumentForm->getAttributeLabel('alias')]) ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-6">
        <?= $form->field($modelDocumentForm, 'status')->dropDownList($modelDocumentForm->statusList,
            [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]
            ]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelDocumentForm, 'position')
            ->textInput(['placeholder' => $modelDocumentForm->getAttributeLabel('position')]) ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-6">
        <?= $form->field($modelDocumentForm, 'annotation')->widget(CKEditor::className(),[
            'editorOptions' => [
                'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                'inline' => false,  //по умолчанию false
                'height' => 300
            ],
        ]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelDocumentForm, 'content')->widget(CKEditor::className(),[
            'editorOptions' => [
                'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                'inline' => false,  //по умолчанию false
                'height' => 300
            ],
        ]); ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-6">
        <?= $form->field($modelDocumentForm, 'meta_keywords')
            ->textarea(['placeholder' => $modelDocumentForm->getAttributeLabel('meta_keywords')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelDocumentForm, 'meta_description')
            ->textarea(['placeholder' => $modelDocumentForm->getAttributeLabel('meta_keywords')]) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($modelDocumentForm, 'field_error')->hiddenInput()->label(false) ?>
    </div>

    <?php if (isset($modelDocumentForm->template)): ?>
        <?php foreach ($modelDocumentForm->template->fields as $modelFieldForm): ?>
            <?php /* @var $modelFieldForm \common\models\forms\FieldForm */ ?>
            <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_INT): ?>
                <?= $this->render('text-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_int',
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_INT_RANGE): ?>
                <?= $this->render('text-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_int',
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FLOAT ||
                $modelFieldForm->type == Constants::FIELD_TYPE_PRICE): ?>
                <?= $this->render('text-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_number',
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FLOAT_RANGE): ?>
                <?= $this->render('text-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_number',
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_STRING ||
                $modelFieldForm->type == Constants::FIELD_TYPE_ADDRESS ||
                $modelFieldForm->type == Constants::FIELD_TYPE_EMAIL ||
                $modelFieldForm->type == Constants::FIELD_TYPE_URL ||
                $modelFieldForm->type == Constants::FIELD_TYPE_SOCIAL ||
                $modelFieldForm->type == Constants::FIELD_TYPE_YOUTUBE): ?>
                <?= $this->render('text-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_string',
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_TEXT): ?>
                <?= $this->render('textarea-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_string',
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_CHECKBOX): ?>
                <?= $this->render('checkboxes-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_int',
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_RADIO): ?>
                <?= $this->render('radios-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_int',
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_LIST): ?>
                <?= $this->render('list-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_int',
                    'modelFieldForm' => $modelFieldForm,
                    'multiple' => false
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_LIST_MULTY): ?>
                <?= $this->render('list-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_int',
                    'modelFieldForm' => $modelFieldForm,
                    'multiple' => true
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_DATE): ?>
                <?= $this->render('date-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'value_string',
                    'attribute2' => 'input_date_to',
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_DATE_RANGE): ?>
                <?= $this->render('date-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'attribute' => 'input_date_from',
                    'attribute2' => 'input_date_to',
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_COUNTRY): ?>
                <?php $id_geo_country = isset($modelDocumentForm->elements_fields[$modelFieldForm->id][0]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][0] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id); ?>
                <?php $placeholder = $fieldsManage->getCountry($id_geo_country); ?>
                <?= $this->render('typeahead-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'modelFieldForm' => $modelFieldForm,
                    'remoteUrl' => '/geo/country/get-country?query=%QUERY',
                    'attribute' => 'value_string',
                    'changeAttribute' => 'id_geo_country',
                    'value' => $placeholder,
                    'hiddenValue' => $id_geo_country
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_REGION): ?>
                <?php $id_geo_region = isset($modelDocumentForm->elements_fields[$modelFieldForm->id][0]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][0] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id); ?>
                <?php $placeholder = $fieldsManage->getRegion($id_geo_region); ?>
                <?= $this->render('typeahead-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'modelFieldForm' => $modelFieldForm,
                    'remoteUrl' => '/geo/region/get-region?query=%QUERY',
                    'attribute' => 'value_string',
                    'changeAttribute' => 'id_geo_region',
                    'value' => $placeholder,
                    'hiddenValue' => $id_geo_region
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_CITY): ?>
                <?php $id_geo_city = isset($modelDocumentForm->elements_fields[$modelFieldForm->id][0]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][0] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id); ?>
                <?php $placeholder = $fieldsManage->getCity($id_geo_city); ?>
                <?= $this->render('typeahead-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'modelFieldForm' => $modelFieldForm,
                    'remoteUrl' => '/geo/city/get-city?query=%QUERY',
                    'attribute' => 'value_string',
                    'changeAttribute' => 'id_geo_city',
                    'value' => $placeholder,
                    'hiddenValue' => $id_geo_city
                ]); ?>
            <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FILE ||
                $modelFieldForm->type == Constants::FIELD_TYPE_FEW_FILES
            ): ?>
                <?= $this->render('file-field', [
                    'containerClass' => 'col-md-12',
                    'form' => $form,
                    'modelDocumentForm' => $modelDocumentForm,
                    'modelFieldForm' => $modelFieldForm,
                ]); ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="clearfix"></div>

    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary text-uppercase']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $url_refresh = Url::to(['element-manage/refresh-elements', 'id_folder' => $modelDocumentForm->parent_id]);
    $id_grid_refresh = '#pjax-grid-elements-block';

    $js = <<< JS
        $('.repeat-name').click(function(){
            var text = $('#documentform-name').val();
            $('#documentform-title').val(text);
        });
        $('.translate-name').click(function(){
            var text = $('#documentform-name').val();
            var text = text.toLowerCase();
            result = translit(text);
            $('#documentform-alias').val(result);
        });
        $('.translate-title').click(function(){
            var text = $('#documentform-title').val();
            var text = text.toLowerCase();
            result = translit(text);
            $('#documentform-alias').val(result);
        });
        $('.selectpicker').selectpicker({});
        $('#form').on('beforeSubmit', function () { 
            var text = $('#documentform-alias').val();
            var text = text.toLowerCase();
            result = translit(text);
            $('#documentform-alias').val(result);
            var form = $(this);
                $.pjax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: new FormData($('#form')[0]),
                    container: "#elements-form-block",
                    push: false,
                    scrollTo: false,
                    cache: false,
                    contentType: false,
                    timeout: 10000,
                    processData: false
                })
                .done(function(data) {
                    try {
                        var result = jQuery.parseJSON(data);
                    } catch (e) {
                        return false;
                    }
                    if(result.success) {
                        // data is saved
                        console.log('success');
                        $("#universal-modal").modal("hide");
                        $.pjax({
                            type: "GET", 
                            url: "$url_refresh",
                            container: "$id_grid_refresh",
                            push: false,
                            timeout: 20000,
                            scrollTo: false
                        });
                    } else if (result.validation) {
                        // server validation failed
                        console.log('validation failed');
                        form.yiiActiveForm('updateMessages', data.validation, true); // renders validation messages at appropriate places
                    } else {
                        // incorrect server response
                        console.log('incorrect server response');
                    }
                })
                .fail(function () {
                    // request failed
                    console.log('request failed');
                })
            return false; // prevent default form submission
        });
        
        function addError(id, message) {
            $( id ).addClass( "has-error" );
            $( id + " .help-block-error" ).text( message ); 
        }
JS;
    $this->registerJs($js); ?>
</div>
