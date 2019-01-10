<?php
/**
 * Created by PhpStorm.
 * User: phpnt.com
 * Date: 06.12.2017
 * Time: 20:38
 */

use yii\bootstrap\Modal;

/* @var $modelPasswordResetRequestForm common\models\forms\PasswordResetRequestForm */
/* @var $this yii\web\View */
?>
<?php
Modal::begin([
    'id' => 'request-password-reset-token-modal',
    'size' => 'modal-sm',
    'header' => false,
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>

<?= $this->render('_request-password-reset-token-form', [
    'modelPasswordResetRequestForm' => $modelPasswordResetRequestForm,
]) ?>

<div class="clearfix"></div>
<?php
Modal::end();
?>
