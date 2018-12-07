<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.12.2018
 * Time: 7:46
 */

/* @var $this \yii\web\View */
/* @var $widget \common\widgets\ViewItems\ViewItems */
?>
<?= $this->render('@frontend/modules/'. $widget->page['alias'] .'/views/templates/item-template', [
    'page' => $widget->page,
    'item' => $widget->selectedItem,
]); ?>
