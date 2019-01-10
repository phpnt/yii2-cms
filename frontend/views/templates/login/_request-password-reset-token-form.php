<?php
/**
 * Created by PhpStorm.
 * User: phpnt.com
 * Date: 06.12.2017
 * Time: 20:45
 */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $modelPasswordResetRequestForm \common\models\forms\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use phpnt\bootstrapNotify\BootstrapNotify;
?>
<div id="request-password-reset-token-block">
    <?= BootstrapNotify::widget() ?>
    <?php $form = ActiveForm::begin([
        'id' => 'form-password-reset',
        'action' => Url::to(['/login/default/request-password-reset']),
        'options' => ['data-pjax' => true]
    ]); ?>

    <?= $form->field($modelPasswordResetRequestForm, 'email')
        ->textInput(['placeholder' => $modelPasswordResetRequestForm->getAttributeLabel('email')])
        ->hint('<i>'.Yii::t('app', 'Введите адрес электронной почты, для сброса пароля').'</i>') ?>

    <div class="form-group text-center">
        <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'btn btn-primary text-uppercase full-width']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $js = <<< JS
        $('#form').on('beforeSubmit', function () { 
            var form = $(this);
                $.pjax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: new FormData($('#form-password-reset')[0]),
                    container: "#request-password-reset-token-block",
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