<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.12.2018
 * Time: 8:41
 */

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */
/* @var $tree array Дерево элемента */
/* @var $templateName string */
?>
<div class="index-<?= $templateName; ?>">
    <?php if ($itemsMenu): ?>
        <?php /* Если есть элементы бокового меню */ ?>
        <div class="row">
            <?= $this->render('_menu', [
                'page' => $page,
                'template' => $template,
                'parent' => $parent,
                'itemsMenu' => $itemsMenu,
                'item' => $item,
                'items' => $items,
                'templateName' => $templateName
            ]); ?>
            <?= $this->render('data', [
                'page' => $page,
                'template' => $template,
                'parent' => $parent,
                'itemsMenu' => $itemsMenu,
                'item' => $item,
                'items' => $items,
                'tree' => $tree,
                'templateName' => $templateName
            ]); ?>
        </div>
    <?php else: ?>
        <div class="row">
            <?php /* Если нет элементов бокового меню */ ?>
            <?= $this->render('data', [
                'page' => $page,
                'template' => $template,
                'parent' => $parent,
                'itemsMenu' => $itemsMenu,
                'item' => $item,
                'items' => $items,
                'tree' => $tree,
                'templateName' => $templateName
            ]); ?>
        </div>
    <?php endif; ?>
</div>