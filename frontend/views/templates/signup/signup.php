<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelSignupForm \common\models\forms\SignupForm */

use yii\bootstrap\Modal;
use common\widgets\oAuth\AuthChoice;
?>
<?php
Modal::begin([
    'id' => 'users-signup',
    'size' => 'modal-md',
    'header' => '<div class="m-b-sm">'.Yii::t('app', 'Войти используя социальную сеть').':</div>'.AuthChoice::widget([
            'baseAuthUrl' => ['/auth/index'],
        ]),
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>

<?= $this->render('_signup-form', ['page' => $page, 'modelSignupForm' => $modelSignupForm]) ?>

<div class="clearfix"></div>
<?php
Modal::end();
?>

