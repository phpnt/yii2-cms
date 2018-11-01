<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.08.2018
 * Time: 11:01
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $modelUserForm \common\models\forms\UserForm */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/login/default/reset-password', 'token' => $modelUserForm->password_reset_token]);
?>
<div class="password-reset">
    <p><?= Yii::t('app', 'Здравствуйте') ?>, <?= Html::encode($modelUserForm->email) ?>,</p>

    <p><?= Yii::t('app', 'Перейдите по ссылке ниже, чтобы сбросить ваш пароль') ?>:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>