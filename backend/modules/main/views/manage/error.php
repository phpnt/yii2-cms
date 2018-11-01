<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p><?= Yii::t('app', 'Вышеупомянутая ошибка возникла, когда веб-сервер обрабатывал ваш запрос.') ?></p>
    <p><?= Yii::t('app', 'Если вы считаете, что это ошибка сервера, пожалуйста, свяжитесь с нами <strong>{email}</strong>. Спасибо.', ['email' => Yii::$app->params['adminEmail']]) ?></p>

</div>
