<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.12.2018
 * Time: 8:41
 */

use frontend\views\templates\control\views\product\assets\ProductTempAsset;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */
/* @var $tree array Дерево элемента */
/* @var $templateName string */

ProductTempAsset::register($this);
?>
<div class="index-<?= $templateName; ?>">
    <?php if ($itemsMenu): ?>
        <?php /* Если есть элементы бокового меню */ ?>
        <div class="row">
            <div class="col-md-3">
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
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
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
            </div>
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
