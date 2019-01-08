<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelLoginForm \common\models\forms\LoginForm */

use yii\bootstrap\Modal;
use common\widgets\oAuth\AuthChoice;
?>
<?php
Modal::begin([
    'id' => 'users-login',
    'size' => 'modal-md',
    'header' => '<div class="col-md-12 m-b-sm">'.Yii::t('app', 'Войти используя социальную сеть').':</div>'.AuthChoice::widget([
            'baseAuthUrl' => ['/auth/index'],
        ]),
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>

<?= $this->render('_login-form', ['page' => $page, 'modelLoginForm' => $modelLoginForm]) ?>

<div class="clearfix"></div>
<?php
Modal::end();
?>
