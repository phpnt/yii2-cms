<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 8:57
 */

use common\widgets\ViewItems\ViewItems;

/* @var $this yii\web\View */
/* @var $alias_menu_item string алиас элемента главного меню */
/* @var $alias_sidebar_item string алиас элемента бокового меню */
?>
<div class="<?= $alias_menu_item; ?>-view-list">
    <?= ViewItems::widget(['alias_menu_item' => $alias_menu_item, 'alias_sidebar_item' => $alias_sidebar_item]); ?>
</div>