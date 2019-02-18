<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 18.02.2019
 * Time: 10:58
 */

use yii\bootstrap\Modal;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $img string */

?>
<?php
Modal::begin([
    'id' => 'modal-show-image',
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center">' . Yii::t('app', 'Просмотр') .'</h3>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
<?= Html::img($img, [
    'class' => 'full-width',
]); ?>
<?php
Modal::end();
?>