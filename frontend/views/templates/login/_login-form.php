<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 02.06.2016
 * Time: 22:23
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use phpnt\ICheck\ICheck;
use phpnt\bootstrapNotify\BootstrapNotify;
use phpnt\animateCss\AnimateCssAsset;
use phpnt\bootstrapSelect\BootstrapSelectAsset;

/* @var $this yii\web\View */
/* @var $page array */
/* @var $modelLoginForm \common\models\forms\LoginForm  */
/* @var $form ActiveForm */

AnimateCssAsset::register($this);
BootstrapSelectAsset::register($this);
?>
<div id="elements-form-block">
    <?= BootstrapNotify::widget() ?>
    <div class="row">
        <?php $form = ActiveForm::begin([
            'id' => 'form',
            'action' => Url::to(['/login/default/index']),
            'options' => ['data-pjax' => true]
        ]); ?>

        <div class="col-md-12">
            <?= $form->field($modelLoginForm, 'email', ['template' => '{label}<div class="input-group">{input}
                            <span class="input-group-addon"><i class="fas fa-envelope"></i></span>
                         </div><i>{hint}</i>{error}'])
                ->textInput(['placeholder' => $modelLoginForm->getAttributeLabel('email')]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($modelLoginForm, 'password', ['template' => '{label}<div class="input-group">{input}
                            <span class="input-group-addon"><i class="fas fa-lock"></i></span>
                         </div><i>{hint}</i>{error}'])
                ->passwordInput(['placeholder' => $modelLoginForm->getAttributeLabel('password')]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($modelLoginForm, 'rememberMe', ['template' => ' {input} {label}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_SQUARE,
                'color'  => 'blue',
                'options' => [
                    'checked' => $modelLoginForm->rememberMe
                ]
            ])->label(false) ?>
        </div>

        <div class="col-md-12">
            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('app', 'Войти'), ['class' => 'btn btn-primary text-uppercase full-width']) ?>
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
        <div class="col-md-12 text-center">
            <?= Html::button(Yii::t('app', 'Забыли пароль?'), [
                'class' => 'btn btn-xs btn-warning',
                'onclick' => '
                    $.pjax({
                        type: "POST",
                        url: "'.Url::to(['/login/default/request-password-reset']).'",  
                        container: "#pjaxModalUniversal2",
                        push: false,
                        scrollTo: false
                    })'])
            ?>
        </div>
    </div>
</div>


