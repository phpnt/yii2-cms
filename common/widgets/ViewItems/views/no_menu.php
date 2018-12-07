<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 06.12.2018
 * Time: 10:00
 */

/* @var $this \yii\web\View */
/* @var $widget \common\widgets\ViewItems\ViewItems */
/* @var $items array */
?>
<div class="row">
    <?php foreach ($items as $item): ?>
        <div class="<?= $widget->itemContainerClass ?>">
            <?= $this->render('@frontend/modules/'. $widget->page['alias'] .'/views/templates/list-item-template', [
                'page' => $widget->page,
                'item' => $item,
            ]); ?>
        </div>
    <?php endforeach; ?>
</div>
