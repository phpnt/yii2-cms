<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 25.08.2018
 * Time: 14:17
 */

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
?>
<?php
$header = $modelDocumentForm->isNewRecord ? Yii::t('app', 'Создать папку') : Yii::t('app', 'Изменить папку');
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-lg',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">' . $header . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="row">
        <?= $this->render('_form-folder', [
            'modelDocumentForm' => $modelDocumentForm,
        ]); ?>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
?>