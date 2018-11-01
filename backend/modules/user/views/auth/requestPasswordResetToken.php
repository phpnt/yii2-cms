<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 24.08.2018
 * Time: 17:04
 */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $modelPasswordResetRequestForm \common\models\forms\PasswordResetRequestForm */
?>
<?php
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-sm',
    'header' => '<h2 class="text-center">' . Yii::t('app', 'Сброс пароля') . '</strong></h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="col-md-12">
        <?= $this->render('_requestPasswordResetToken-form', [
            'modelPasswordResetRequestForm' => $modelPasswordResetRequestForm,
        ]) ?>
    </div>

    <div class="clearfix"></div>
<?php
Modal::end();
