<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 19:06
 */

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $modelUserForm \common\models\forms\UserForm */
?>
<?php
$header = $modelUserForm->isNewRecord ? Yii::t('app', 'Создать пользователя') : Yii::t('app', 'Изменить пользователя');
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-md',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">' . $header . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="row">
        <?= $this->render('_form-user', [
            'modelUserForm' => $modelUserForm,
        ]); ?>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
?>