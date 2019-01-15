<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelLoginForm \common\models\forms\LoginForm */

use yii\bootstrap\Modal;
use common\widgets\oAuth\AuthChoice;

$header = isset(Yii::$app->authClientCollection) ?
    '<div class="col-md-12 m-b-sm">' . Yii::t('app', 'Войти используя социальную сеть') . ':</div>'.AuthChoice::widget([
        'baseAuthUrl' => ['/auth/index'],
        'popupMode' => false
    ]) :
    '<div class="col-md-12 text-center">' . Yii::t('app', 'Регистрация пользователя') . '</div>';
?>
<?php
Modal::begin([
    'id' => 'users-login',
    'size' => 'modal-md',
    'header' => $header,
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>

<?= $this->render('_login-form', ['page' => $page, 'modelLoginForm' => $modelLoginForm]) ?>

<div class="clearfix"></div>
<?php
Modal::end();
?>
