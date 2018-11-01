<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.08.2018
 * Time: 12:13
 */

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use backend\modules\user\assets\UserAsset;

/* @var $this yii\web\View */
/* @var $modelResetPasswordForm \common\models\forms\ResetPasswordForm */

$this->title = Yii::t('app', 'Сброс пароля');
$this->params['breadcrumbs'][] = $this->title;
UserAsset::register($this);
?>

<p class="login-box-msg"><?= Yii::t('app', 'Введите новый пароль') ?></p>

<?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

<?= $form->field($modelResetPasswordForm, 'password')->passwordInput(['autofocus' => true]) ?>

<div class="form-group text-center">
    <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<div class="clearfix"></div>
