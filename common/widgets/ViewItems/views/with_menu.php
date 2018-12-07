<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 06.12.2018
 * Time: 10:10
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
        <?php foreach ($items as $item): ?>
            <div class="<?= $widget->itemContainerClass ?>">
                <?= $this->render('@frontend/modules/'. $widget->page['alias'] .'/views/templates/list-item-template', [
                    'page' => $widget->page,
                    'item' => $item,
                ]); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>