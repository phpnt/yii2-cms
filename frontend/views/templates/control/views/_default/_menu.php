<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 12:21
 */

use yii\widgets\Menu;
use frontend\views\templates\assets\MetisMenuAsset;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */
/* @var $templateName string */

MetisMenuAsset::register($this);
?>
<div class="col-md-12">
    <div class="row">
        <div class="col-xs-12">
            <?php p($this->viewFile); ?>
            <div class="menu-<?= $templateName; ?>">
                <div class="sidebar-nav">
                    <?= Menu::widget([
                        'items' => $itemsMenu,
                        'options' => [
                            'class' => 'side-menu metismenu',
                        ],
                        'activeCssClass'=>'active',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
