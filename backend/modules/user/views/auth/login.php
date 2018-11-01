<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\modules\user\assets\UserAsset;
use phpnt\ICheck\ICheck;

/* @var $this yii\web\View */
/* @var $modelLoginForm \common\models\forms\LoginForm */

$this->title = Yii::t('app', 'Вход на сайт');
$this->params['breadcrumbs'][] = $this->title;
UserAsset::register($this);
?>

<p class="login-box-msg"><?= Yii::t('app', 'Авторизуйтесь, чтобы перейти к панели управления сайтом.') ?></p>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'fieldConfig' => [
        'options' => [
            //'tag' => false,
        ],
    ],
]); ?>

<div class="col-md-12">
    <?= $form->field($modelLoginForm, 'email', ['template' => '{label}<div class="form-group has-feedback">{input} 
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div><i>{hint}</i>{error}'])
        ->textInput(['placeholder' => $modelLoginForm->getAttributeLabel('email')])->label(false) ?>
</div>

<div class="col-md-12">
    <?= $form->field($modelLoginForm, 'password', ['template' => '{label}<div class="form-group has-feedback">{input} 
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div><i>{hint}</i>{error}'])
        ->passwordInput(['placeholder' => $modelLoginForm->getAttributeLabel('password')])->label(false) ?>
</div>

<div class="col-md-12">
    <?= $form->field($modelLoginForm, 'rememberMe', ['template' => '{input}'])->widget(ICheck::className(), [
        'type'  => ICheck::TYPE_CHECBOX,
        'style'  => ICheck::STYLE_MIMIMAL,
        'color'  => 'blue'                  // цвет
    ]) ?>
</div>

<div class="col-md-12 text-center m-t-md">
    <?= Html::submitButton(''.Yii::t('app', 'Войти'), [
        'class' => 'btn btn-primary'
    ]) ?>
</div>

<div class="col-md-12 text-center m-t-sm">
    <?= Html::button(Yii::t('app', 'Я забыл свой пароль'), [
        'class' => 'link',
        'title' => Yii::t('app', 'Я забыл свой пароль'),
        'onclick' => '
                $.pjax({
                    type: "GET",
                    url: "' . Url::to(['/user/request-password-reset']) . '",
                    container: "#pjaxModalUniversal",
                    push: false,
                    timeout: 10000,
                    scrollTo: false
                })'
    ]);
    ?>
</div>

<?php ActiveForm::end(); ?>
<div class="clearfix"></div>
