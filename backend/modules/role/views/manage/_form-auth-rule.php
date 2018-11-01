<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 13:57
 */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $modelAuthRuleForm \common\models\forms\AuthRuleForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
?>
<div id="elements-form-block">
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => $modelAuthRuleForm->isNewRecord ? Url::to(['create-auth-rule']) : Url::to(['update-auth-rule', 'name' => $modelAuthRuleForm->name]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="col-md-12">
        <?= $form->field($modelAuthRuleForm, 'name')
            ->textInput(['placeholder' => $modelAuthRuleForm->getAttributeLabel('name')]) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($modelAuthRuleForm, 'data')
            ->textInput(['placeholder' => $modelAuthRuleForm->getAttributeLabel('data')]) ?>
    </div>

    <div class="clearfix"></div>

    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary text-uppercase']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $url_refresh = Url::to(['refresh-auth-rule']);
    $id_grid_refresh = '#pjax-grid-auth-rule-block';

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
                $("#universal-modal").modal("hide");
                    $.pjax({
                        type: "GET", 
                        url: "$url_refresh",
                        container: "$id_grid_refresh",
                        push: false,
                        timeout: 20000,
                        scrollTo: false
                    });
            })
        return false; // prevent default form submission
    });
JS;
    $this->registerJs($js); ?>
</div>