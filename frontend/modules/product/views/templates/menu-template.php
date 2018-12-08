<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.12.2018
 * Time: 8:39
 */

use yii\widgets\Menu;

/* @var $this \yii\web\View */
/* @var $itemsMenu array */
?>
<?php /* Отображение бокового меню */ ?>
<div class="sidebar-nav">
    <?= Menu::widget([
        'items' => $itemsMenu,
        'options' => [
            'class' => 'side-menu metismenu',
        ],
        'activeCssClass'=>'active',
    ]); ?>
</div>
