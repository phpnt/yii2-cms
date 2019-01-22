<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 13:14
 */

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */
/* @var $templateName string */

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="col-md-9">
    <div class="row">
        <div class="col-md-12">
            <div class="content-<?= $templateName; ?>">
                <?= Yii::t('app', $page['content']); ?>
            </div>
        </div>
    </div>
</div>