<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 02.06.2016
 * Time: 22:10
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Html;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $page array */
/* @var $modelSignupForm \common\models\forms\SignupForm */
/* @var $key integer */

?>
<div id="elements-form-block">
    <?= BootstrapNotify::widget() ?>
    <?php BootstrapSelectAsset::register($this) ?>
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => Url::to(['/signup/default/index']),
        'options' => ['data-pjax' => true]
    ]); ?>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($modelSignupForm, 'email', ['template' => '{label} 
                                            <div class="input-group">
                                                {input}<span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                             </div>
                                            <i>{hint}</i>{error}'])
                ->textInput(['placeholder' => $modelSignupForm->getAttributeLabel('email')]) ?>
        </div>
        <div class="col-sm-12">
            <?= $form->field($modelSignupForm, 'password', ['template' => '{label} 
                                            <div class="input-group">
                                                {input}<span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                             </div>
                                            <i>{hint}</i>{error}'])
                ->passwordInput(['placeholder' => $modelSignupForm->getAttributeLabel('password')]) ?>
        </div>
        <div class="col-sm-12">
            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('app', 'Регистрация'), ['class' => 'btn btn-primary text-uppercase full-width']) ?>
            </div>
        </div>
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