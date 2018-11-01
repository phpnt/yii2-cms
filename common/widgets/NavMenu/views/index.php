<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 8:41
 */

use yii\bootstrap\Nav;

/* @var $this \yii\web\View */
/* @var $widget \common\widgets\NavMenu\NavMenu */
/* @var $items array */
?>
<?= Nav::widget([
    'items' => $items,
    'options' => $widget->optionsNav,
]);  ?>
