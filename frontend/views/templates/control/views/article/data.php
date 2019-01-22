<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 12:20
 */

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */
/* @var $templateName string */
/* @var $tree array Дерево элемента */
?>
<div class="data-<?= $templateName; ?>">
    <?php if ($item): ?>
    <?php /* Если выбран элемент из списка */ ?>
        <?= $this->render('_item', [
            'page' => $page,
            'template' => $template,
            'parent' => $parent,
            'itemsMenu' => $itemsMenu,
            'item' => $item,
            'items' => $items,
            'tree' => $tree,
            'templateName' => $templateName
        ]); ?>
    <?php elseif ($items): ?>
        <?php /* Если отображается список */ ?>
        <?= $this->render('_list', [
            'page' => $page,
            'template' => $template,
            'parent' => $parent,
            'itemsMenu' => $itemsMenu,
            'item' => $item,
            'items' => $items,
            'tree' => $tree,
            'templateName' => $templateName
        ]); ?>
    <?php else: ?>
        <?php /* Если нет ни элемента, ни списка */ ?>
        <?= $this->render('_content', [
            'page' => $page,
            'template' => $template,
            'parent' => $parent,
            'itemsMenu' => $itemsMenu,
            'item' => $item,
            'items' => $items,
            'templateName' => $templateName
        ]); ?>
    <?php endif; ?>
</div>
