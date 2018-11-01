<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 13:23
 */

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $modelAuthItemChildForm \common\models\forms\AuthItemChildForm */
?>
<?php
$header = $modelAuthItemChildForm->isNewRecord ? Yii::t('app', 'Создать наследование RBAC') : Yii::t('app', 'Изменить наследование RBAC');
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-md',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">' . $header . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="row">
        <?= $this->render('_form-auth-item-child', [
            'modelAuthItemChildForm' => $modelAuthItemChildForm,
        ]); ?>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
?>