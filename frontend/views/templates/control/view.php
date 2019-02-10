<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 17:16
 */

use common\widgets\ViewItems\ViewItems;
use common\widgets\Rating\Rating;
use common\widgets\Comment\Comment;
use common\widgets\Basket\BasketButton;

/* @var $this yii\web\View */
/* @var $alias_menu_item string алиас элемента главного меню */
/* @var $alias_sidebar_item string алиас элемента бокового меню */
/* @var $modelDocumentForm \common\models\forms\DocumentForm выбранный элемент */
?>
<div class="<?= $alias_menu_item; ?>-view">
    <?= ViewItems::widget(['alias_menu_item' => $alias_menu_item, 'alias_sidebar_item' => $alias_sidebar_item, 'modelDocumentForm' => $modelDocumentForm]); ?>
</div>

