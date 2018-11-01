<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelLoginForm \common\models\forms\LoginForm */

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-default-index">
    <div class="col-md-12">
        <?= $page['content'] ?>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
        ]); ?>

        <?= $form->field($modelLoginForm, 'email')->textInput([
            'maxlength' => true,
            'placeholder' => $modelLoginForm->getAttributeLabel('email')
        ]); ?>

        <?= $form->field($modelLoginForm, 'password')->passwordInput([
            'maxlength' => true,
            'placeholder' => $modelLoginForm->getAttributeLabel('password')
        ]);?>

        <?= $form->field($modelLoginForm, 'rememberMe')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Войти'), [
                'class' => 'btn btn-primary',
                'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <?= Html::a(Yii::t('app', 'Я забыл свой пароль'), ['/login/default/request-password-reset']) ?>
    </div>
</div>
