<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 09.01.2019
 * Time: 8:20
 */

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\widgets\TemplateOfElement\SetProfileFields;
use phpnt\bootstrapSelect\BootstrapSelectAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelProfileTemplateForm \common\widgets\TemplateOfElement\forms\ProfileTemplateForm */
/* @var $modelUserForm \common\models\extend\UserExtend */
/* @var $profiles array */

BootstrapSelectAsset::register($this);
?>
<div id="elements-form-block">
    <?php $form = ActiveForm::begin([
        'id' => 'form-profile',
        'action' => $modelProfileTemplateForm->isNewRecord ? Url::to(['create-profile', 'id_document' => $modelProfileTemplateForm->id]) : Url::to(['update-profile', 'id_document' => $modelProfileTemplateForm->id, 'id_folder' => $modelProfileTemplateForm->parent_id]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <?php $profiles = $modelProfileTemplateForm->getSelectProfile($page); ?>

    <?php if (count($profiles) > 1): ?>
        <?= $form->field($modelProfileTemplateForm, 'parent_id')->dropDownList($profiles,
            [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---',
                    'size' => 10
                ],
                'onchange' => '
                    $.pjax({
                        type: "POST", 
                        url: "'.Url::to(['create-profile']).'",
                        data: $("#form-profile").serializeArray(),
                        container: "#elements-form-block",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    });
                '
            ]) ?>
    <?php elseif (count($profiles) == 1): ?>
        <?php $modelProfileTemplateForm->template_id = $profiles[0]['template_id']; ?>
        <?php $modelProfileTemplateForm->parent_id = $profiles[0]['id']; ?>
        <?= $form->field($modelProfileTemplateForm, 'parent_id')->hiddenInput()->label(false) ?>
    <?php endif; ?>

    <?php if ($modelProfileTemplateForm->template_id): ?>
        <?php if (isset($modelProfileTemplateForm->template)): ?>
            <div class="row">
                <?= SetProfileFields::widget([
                    'form' => $form,
                    'model' => $modelProfileTemplateForm,
                ]); ?>
            </div>
        <?php endif; ?>

        <?= $form->field($modelProfileTemplateForm, 'id')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'name')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'alias')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'status')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'created_by')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'updated_by')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'template_id')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'url')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'container')->hiddenInput()->label(false) ?>

        <div class="form-group text-center">
            <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary text-uppercase']) ?>
        </div>

    <?php else: ?>
        <?= $form->field($modelProfileTemplateForm, 'url')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'container')->hiddenInput()->label(false) ?>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>
    <?php
    $js = <<< JS
        $('#form-profile').on('beforeSubmit', function () { 
            var form = $(this);
                $.pjax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: new FormData($('#form-profile')[0]),
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
                        $("#profile-modal").modal("hide");
                        $.pjax({
                            type: "GET", 
                            url: result.url,
                            container: result.container,
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
