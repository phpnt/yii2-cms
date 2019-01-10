<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 8:42
 */

use frontend\views\templates\assets\PageAsset;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */

PageAsset::register($this);
?>
<?php if (!$parent && !$itemsMenu && !$items): ?>
    <?php /* Если корневая папка основного меню, без бокового меню и без элементов в папке. */ ?>
    <?= $this->render('no-items', [
        'page' => $page
    ]); ?>
<?php elseif(!$parent && !$itemsMenu && $items): ?>
    <?php /* Если корневая папка основного меню, без бокового меню и есть элементы в корневой папке. */ ?>
    <?= $this->render('items', [
        'page' => $page,
        'template' => $template,
        'items' => $items,
    ]); ?>
<?php elseif($parent && !$itemsMenu && $item): ?>
    <?php /* Если корневая папка основного меню, без бокового меню и выбран элемент в корневой папке. */ ?>
    <?= $this->render('selected-item', [
        'page' => $page,
        'template' => $template,
        'item' => $item,
    ]); ?>
<?php elseif(!$parent && $itemsMenu && !$items): ?>
    <?php /* Если корневая папка основного меню, с бокововым меню, без элементов в папке. */ ?>
    <?= $this->render('sidebar-noitems', [
        'page' => $page,
        'itemsMenu' => $itemsMenu,
    ]); ?>
<?php elseif($parent && $itemsMenu && !$items && !$item): ?>
    <?php /* Если папка бокового меню, без элементов в папке. */ ?>
    <?= $this->render('sidebar-items', [
        'page' => $page,
        'template' => $template,
        'parent' => $parent,
        'items' => $items ? $items : [],
        'itemsMenu' => $itemsMenu,
    ]); ?>
<?php elseif($parent && $itemsMenu && $items): ?>
    <?php /* Если папка бокового меню, с элементами в папке. */ ?>
    <?= $this->render('sidebar-items', [
        'page' => $page,
        'template' => $template,
        'parent' => $parent,
        'items' => $items ? $items : [],
        'itemsMenu' => $itemsMenu,
    ]); ?>
<?php elseif($parent && $itemsMenu && $item): ?>
    <?php /* Если папка бокового меню, с выбранным элементом. */ ?>
    <?= $this->render('sidebar-selected-item', [
        'page' => $page,
        'template' => $template,
        'parent' => $parent,
        'item' => $item,
        'itemsMenu' => $itemsMenu,
    ]); ?>
<?php endif; ?>

