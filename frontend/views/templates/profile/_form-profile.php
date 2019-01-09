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
use common\widgets\TemplateOfElement\SetDefaultFields;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $profile array */
/* @var $modelProfileTemplateForm \common\widgets\TemplateOfElement\forms\ProfileTemplateForm */
?>
<div id="elements-form-block">
    <div class="col-md-12">
        <?php p($this->viewFile); ?>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'form-profile',
        'action' => $modelProfileTemplateForm->isNewRecord ? Url::to(['save-profile', 'id_document' => $modelProfileTemplateForm->id]) : Url::to(['update-profile', 'id_document' => $modelProfileTemplateForm->id, 'id_folder' => $modelProfileTemplateForm->parent_id]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="col-md-12">
        <?= $form->field($modelProfileTemplateForm, 'field_error')->hiddenInput()->label(false) ?>
    </div>

    <?php if (isset($modelProfileTemplateForm->template)): ?>
        <?= SetDefaultFields::widget([
            'form' => $form,
            'model' => $modelProfileTemplateForm,
            'modelName' => 'ProfileTemplateForm',
        ]); ?>
    <?php endif; ?>

    <div class="clearfix"></div>

    <div class="col-md-12">
        <?= $form->field($modelProfileTemplateForm, 'id')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'name')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'alias')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'status')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'created_by')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'updated_by')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'parent_id')->hiddenInput()->label(false) ?>
        <?= $form->field($modelProfileTemplateForm, 'template_id')->hiddenInput()->label(false) ?>
    </div>

    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary text-uppercase']) ?>
    </div>
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
                    console.log('done');
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
