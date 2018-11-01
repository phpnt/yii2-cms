<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 24.09.2018
 * Time: 5:19
 */

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $modelFieldForm \common\models\forms\FieldForm */
?>
<?php
$header = $modelFieldForm->isNewRecord ? Yii::t('app', 'Добавить поле') : Yii::t('app', 'Изменить поле');
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-md',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">' . $header . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="row">
        <?= $this->render('_form-field', [
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
?>