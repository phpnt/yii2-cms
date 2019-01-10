<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 15:37
 */

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */
?>
<div class="sidebar-content-items">
    <div class="col-md-12">
        <?php p($this->viewFile); ?>
    </div>
    <div class="col-md-12">
        <div class="row">
            <?php foreach ($items as $item): ?>
                <?php if ($template['mark'] == 'default' || $template['mark'] == ''): ?>
                    <div class="col-md-4">
                        <div class="row">
                            <?= $this->render('default/__sidebar-item-of-list', [
                                'page' => $page,
                                'parent' => $parent,
                                'item' => $item,
                            ]); ?>
                        </div>
                    </div>
                <?php elseif ($template['mark'] == 'youtube'): ?>
                    <div class="col-md-4">
                        <div class="row">
                            <?= $this->render('youtube/__sidebar-item-of-list', [
                                'page' => $page,
                                'parent' => $parent,
                                'item' => $item,
                            ]); ?>
                        </div>
                    </div>
                <?php elseif ($template['mark'] == 'article'): ?>
                    <div class="col-md-12">
                        <div class="row">
                            <?= $this->render('article/__sidebar-item-of-list', [
                                'page' => $page,
                                'parent' => $parent,
                                'item' => $item,
                            ]); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-12">
                        <?= Yii::t('app', 'Необходимо создать новое представление для меню шаблона {temp}.', ['temp' => $template['mark']]); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

