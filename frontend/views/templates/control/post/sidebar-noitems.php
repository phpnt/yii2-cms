<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 14:39
 */

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $itemsMenu array Элементы меню */

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sidebar-noitems">
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
            <?= $this->render('_sidebar-content-noitems', [
                'page' => $page
            ]); ?>
        </div>
    </div>
</div>
