<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 24.08.2018
 * Time: 17:10
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $modelPasswordResetRequestForm \common\models\forms\PasswordResetRequestForm */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="elements-form-block">
    <?php BootstrapNotify::widget() ?>
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'options' => [
            'enctype' => 'multipart/form-data',
            'data-pjax' => true
        ]
    ]); ?>

    <div class="col-sm-12">
        <?= $form->field($modelPasswordResetRequestForm, 'email', ['template' => '{label}<div class="form-group has-feedback">{input} 
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div><i>{hint}</i>{error}'])
            ->textInput(['placeholder' => $modelPasswordResetRequestForm->getAttributeLabel('email')])->label(false) ?>
    </div>

    <div class="clearfix"></div>

    <div class="form-group text-center m-t-lg">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $js = <<< JS
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