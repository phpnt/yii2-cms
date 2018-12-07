<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.12.2018
 * Time: 8:00
 */

/* @var $this \yii\web\View */
/* @var $widget \common\widgets\ViewItems\ViewItems */
/* @var $items array */
/* @var $itemsMenu array */
?>
<div class="row">
    <div class="<?= $widget->menuContainerClass ?>">
        <div class="sidebar-nav">
            <?= $this->render('@frontend/modules/'. $widget->page['alias'] .'/views/templates/menu-template', [
                'itemsMenu' => $itemsMenu,
            ]); ?>
        </div>
    </div>
    <div class="<?= $widget->itemsMenuContainerClass ?>">
        <?= $this->render('@frontend/modules/'. $widget->page['alias'] .'/views/templates/item-template', [
            'page' => $widget->page,
            'item' => $widget->selectedItem,
        ]); ?>
    </div>
</div>