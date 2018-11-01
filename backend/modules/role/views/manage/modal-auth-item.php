<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 12:27
 */

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $modelAuthItemForm \common\models\forms\AuthItemForm */
?>
<?php
$header = $modelAuthItemForm->isNewRecord ? Yii::t('app', 'Создать роль или разрешение') : Yii::t('app', 'Изменить роль или разрешение');
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-md',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">' . $header . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="row">
        <?= $this->render('_form-auth-item', [
            'modelAuthItemForm' => $modelAuthItemForm,
        ]); ?>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
?>