<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use common\widgets\ViewItems\ViewItems;

/* @var $this yii\web\View */
/* @var $alias_menu_item string алиас элемента главного меню */
?>
<div class="<?= $alias_menu_item; ?>-index">
    <?= ViewItems::widget(['alias_menu_item' => $alias_menu_item]); ?>
</div>
