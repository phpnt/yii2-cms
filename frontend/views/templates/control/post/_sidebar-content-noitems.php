<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 15:27
 */

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $itemsMenu array Элементы меню */
?>
<div class="sidebar-noitems-content">
    <div class="col-xs-12">
        <?php p($this->viewFile); ?>
    </div>
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']); ?>
    </div>
</div>
