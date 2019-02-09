<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 28.08.2018
 * Time: 22:46
 */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use phpnt\ICheck\ICheck;

/* @var $this yii\web\View */
/* @var $modelTemplateForm \common\models\forms\TemplateForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
?>
<div id="elements-form-block">
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => $modelTemplateForm->isNewRecord ? Url::to(['create-template']) : Url::to(['update-template', 'id' => $modelTemplateForm->id]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="col-md-6">
        <?= $form->field($modelTemplateForm, 'name')
            ->textInput(['placeholder' => $modelTemplateForm->getAttributeLabel('name')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelTemplateForm, 'mark')
            ->textInput(['placeholder' => $modelTemplateForm->getAttributeLabel('mark')]) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($modelTemplateForm, 'description')
            ->textarea(['placeholder' => $modelTemplateForm->getAttributeLabel('description')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelTemplateForm, 'status')->dropDownList($modelTemplateForm->statusList,
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
        <?= $form->field($modelTemplateForm, 'i18n')->dropDownList($modelTemplateForm->statusI18nList,
            [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]
            ]) ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-12">
        <?= $form->field($modelTemplateForm, 'add_rating', ['template' => ' {input} {label}'])->widget(ICheck::class, [
            'type'  => ICheck::TYPE_CHECBOX,
            'style'  => ICheck::STYLE_SQUARE,
            'color'  => 'blue',
            'options' => [
                'checked' => $modelTemplateForm->add_rating
            ]
        ])->label(false) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($modelTemplateForm, 'add_comments', ['template' => ' {input} {label}'])->widget(ICheck::class, [
            'type'  => ICheck::TYPE_CHECBOX,
            'style'  => ICheck::STYLE_SQUARE,
            'color'  => 'blue',
            'options' => [
                'checked' => $modelTemplateForm->add_comments
            ]
        ])->label(false) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($modelTemplateForm, 'use_filter', ['template' => ' {input} {label}'])->widget(ICheck::class, [
            'type'  => ICheck::TYPE_CHECBOX,
            'style'  => ICheck::STYLE_SQUARE,
            'color'  => 'blue',
            'options' => [
                'checked' => $modelTemplateForm->use_filter
            ]
        ])->label(false) ?>
    </div>

    <div class="clearfix"></div>

    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary text-uppercase']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $url_refresh = Url::to(['/document/template-manage/refresh-templates']);
    $id_grid_refresh = '#pjax-grid-templates-block';

    $js = <<< JS
        $('.selectpicker').selectpicker({});
        $('#form').on('beforeSubmit', function () { 
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
JS;
    $this->registerJs($js); ?>
</div>
