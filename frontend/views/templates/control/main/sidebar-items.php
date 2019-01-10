<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 14:59
 */

use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */

$this->title = Yii::t('app', $parent['name']);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $page['name']),
    'url' => Url::to(['/control/default/index', 'alias' => $page['alias']])
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sidebar-items">
    <div class="col-md-12">
        <?php p($this->viewFile); ?>
    </div>
    <div class="col-md-3">
        <div class="row">
            <?= $this->render('sidebar-menu', [
                'page' => $page,
                'itemsMenu' => $itemsMenu,
            ]); ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <?= $this->render('_sidebar-content-items', [
                'page' => $page,
                'template' => $template,
                'parent' => $parent,
                'items' => $items,
            ]); ?>
        </div>
    </div>
</div>
