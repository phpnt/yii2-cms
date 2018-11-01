<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 13:25
 */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $modelAuthItemChildForm \common\models\forms\AuthItemChildForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
?>
<div id="elements-form-block">
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => $modelAuthItemChildForm->isNewRecord ? Url::to(['create-auth-item-child']) : Url::to(['update-auth-item-child', 'parent' => $modelAuthItemChildForm->parent]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="col-md-6">
        <?= $form->field($modelAuthItemChildForm, 'parent')
            ->textInput(['placeholder' => $modelAuthItemChildForm->getAttributeLabel('parent')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelAuthItemChildForm, 'child')
            ->textInput(['placeholder' => $modelAuthItemChildForm->getAttributeLabel('child')]) ?>
    </div>

    <div class="clearfix"></div>

    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary text-uppercase']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $url_refresh = Url::to(['refresh-auth-item-child']);
    $id_grid_refresh = '#pjax-grid-auth-item-child-block';

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
                        timeout: 30000,
                        scrollTo: false
                    });
            })
        return false; // prevent default form submission
    });
JS;
    $this->registerJs($js); ?>
</div>