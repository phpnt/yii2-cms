<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.08.2018
 * Time: 13:54
 */

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
?>
<?php
$header = $modelDocumentForm->isNewRecord ? Yii::t('app', 'Создать элемент') : Yii::t('app', 'Изменить элемент');
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-lg',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">' . $header . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="row">
        <?= $this->render('_form-element', [
            'modelDocumentForm' => $modelDocumentForm,
        ]); ?>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
?>
