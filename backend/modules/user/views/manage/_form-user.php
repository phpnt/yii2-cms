<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 19:07
 */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $modelUserForm \common\models\forms\UserForm */
?>
<div id="elements-form-block">
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => $modelUserForm->isNewRecord ? Url::to(['create-user']) : Url::to(['update-user', 'id' => $modelUserForm->id]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="col-md-6">
        <?= $form->field($modelUserForm, 'first_name')
            ->textInput(['placeholder' => $modelUserForm->getAttributeLabel('first_name')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelUserForm, 'last_name')
            ->textInput(['placeholder' => $modelUserForm->getAttributeLabel('last_name')]) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($modelUserForm, 'status')->dropDownList($modelUserForm->statusList,
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
        <?= $form->field($modelUserForm, 'email')
            ->textInput(['placeholder' => $modelUserForm->getAttributeLabel('email')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelUserForm, 'role')->dropDownList($modelUserForm->userRoles,
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
        <?= $form->field($modelUserForm, 'password')
            ->passwordInput(['placeholder' => $modelUserForm->getAttributeLabel('password')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelUserForm, 'password_confirm')
            ->passwordInput(['placeholder' => $modelUserForm->getAttributeLabel('password_confirm')]) ?>
    </div>

    <div class="clearfix"></div>

    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary text-uppercase']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $url_refresh = Url::to(['refresh-user']);
    $id_grid_refresh = '#pjax-grid-user-block';

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
                timeout: 30000,
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
                        timeout: 30000,
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
                $("#universal-modal").modal("hide");
                    $.pjax({
                        type: "GET", 
                        url: "$url_refresh",
                        container: "$id_grid_refresh",
                        push: false,
                        timeout: 30000,
                        scrollTo: false
                    });
            })
        return false; // prevent default form submission
    });
JS;
    $this->registerJs($js); ?>
</div>