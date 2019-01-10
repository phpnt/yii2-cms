<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.12.2018
 * Time: 8:39
 */

use yii\widgets\Menu;
use frontend\views\templates\assets\MetisMenuAsset;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $itemsMenu array Элементы меню */
MetisMenuAsset::register($this);
?>
<?php /* Отображение бокового меню */ ?>
<div class="menu-template">
    <div class="col-xs-12">
        <?php p($this->viewFile); ?>
    </div>
    <div class="col-xs-12">
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