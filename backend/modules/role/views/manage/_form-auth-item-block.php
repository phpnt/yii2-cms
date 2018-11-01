<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 23:34
 */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use dosamigos\multiselect\MultiSelectListBox;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $modelAuthItemForm \common\models\forms\AuthItemForm */
?>
<div id="auth-item-form-block">
    <div class="row">
        <?= BootstrapNotify::widget() ?>
        <?php $form = ActiveForm::begin([
            'id' => 'auth-item-form',
            'action' => Url::to(['/role/manage/update-auth-item', 'name' => $modelAuthItemForm->name]),
            'options' => ['data-pjax' => true]
        ]); ?>

        <div class="col-md-6">
            <?= $form->field($modelAuthItemForm, 'name')->dropDownList($modelAuthItemForm->getPermissionList(),
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
                            url: "'.Url::to(['/role/manage/refresh-auth-item-form']).'?name=" + $(this).val(),
                            container: "#auth-item-form-block",
                            push: false,
                            timeout: 10000,
                            scrollTo: false
                        });'
                ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($modelAuthItemForm, 'type')->dropDownList($modelAuthItemForm->getTypeList(),
                [
                    'class'  => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-default',
                        'live-search' => 'false',
                        'title' => '---'
                    ]
                ]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($modelAuthItemForm, 'description')
                ->textarea(['placeholder' => $modelAuthItemForm->getAttributeLabel('description')]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($modelAuthItemForm, 'rule_name')->dropDownList($modelAuthItemForm->getRuleList(),
                [
                    'class'  => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-default',
                        'live-search' => 'false',
                        'title' => '---'
                    ]
                ]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($modelAuthItemForm, 'data')
                ->textInput(['placeholder' => $modelAuthItemForm->getAttributeLabel('data')]) ?>
        </div>

        <?php if ($modelAuthItemForm->type == \common\models\Constants::TYPE_ROLE): ?>
            <div class="col-md-12">
                <?= MultiSelectListBox::widget([
                    'id'=>"authitemform-permission_list",
                    "options" => ['multiple'=>"multiple"], // for the actual multiselect
                    'data' => $modelAuthItemForm->getAllPermissionList(), // data as array
                    'value' => $modelAuthItemForm->getUsedPermissionList(), // if preselected
                    'name' => 'AuthItemForm[permission_list]', // name for the form
                    "clientOptions" =>
                        [
                            "includeSelectAllOption" => true,
                            'numberDisplayed' => 2
                        ],
                ]); ?>
            </div>
        <?php endif; ?>

        <div class="clearfix"></div>

        <div class="form-group text-center m-t-md">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary text-uppercase']) ?>
        </div>
        <?php ActiveForm::end(); ?>

        <?php
        $url_refresh = Url::to(['refresh-auth-item']);
        $id_grid_refresh = '#pjax-grid-auth-item-block';

        $js = <<< JS
            $('.selectpicker').selectpicker({});
            $('#auth-item-form').on('beforeSubmit', function () { 
                var form = $(this);
                    $.pjax({
                        type: form.attr('method'),
                        url: form.attr('action'),
                        data: new FormData($('#auth-item-form')[0]),
                        container: "#auth-item-form-block",
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
                            console.log(result);
                            $.pjax({
                                type: "GET", 
                                url: "$url_refresh?name=" + result.name,
                                container: "$id_grid_refresh",
                                push: false,
                                timeout: 10000,
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
</div>