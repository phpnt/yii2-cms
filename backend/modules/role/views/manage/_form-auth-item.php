<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 12:28
 */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $modelAuthItemForm \common\models\forms\AuthItemForm */
?>
<div id="elements-form-block">
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => $modelAuthItemForm->isNewRecord ? Url::to(['create-auth-item']) : Url::to(['update-auth-item', 'name' => $modelAuthItemForm->name]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="col-md-6">
        <?= $form->field($modelAuthItemForm, 'name')
            ->textInput(['placeholder' => $modelAuthItemForm->getAttributeLabel('name')])->hint('<i>' . Yii::t('app', 'Маршрут к действию контроллера.') . '</i>') ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelAuthItemForm, 'type')->dropDownList([1 => Yii::t('app', 'Роль'), 2 => Yii::t('app', 'Разрешение')],
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

    <div class="clearfix"></div>

    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary text-uppercase']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $url_refresh = Url::to(['refresh-auth-item']);
    $id_grid_refresh = '#pjax-grid-auth-item-block';

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