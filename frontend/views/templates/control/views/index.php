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
/* @var $tree array Дерево элемента */

PageAsset::register($this);

$templateName = $template['mark'] ? $template['mark'] : 'default';
?>
<?php /* Если корневая папка основного меню, без бокового меню и без элементов в папке. */ ?>
<?php $file = Yii::getAlias( '@frontend/views/templates/control/views/' . $templateName . '/index.php'); ?>
<?php /* Если шаблон существует, выводим его, если нет, то выводим шаблон по умолчанию */ ?>
<?php if(file_exists($file)): ?>
    <?= $this->render($templateName . '/index', [
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
    <?= $this->render('_default/index', [
        'page' => $page,
        'template' => $template,
        'parent' => $parent,
        'itemsMenu' => $itemsMenu,
        'item' => $item,
        'items' => $items,
        'tree' => $tree,
        'templateName' => $templateName
    ]); ?>
<?php endif; ?>
