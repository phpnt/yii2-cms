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

$this->title = Yii::t('app', $page['title']);

$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="index">
    <div class="col-md-12">
        <?php p($this->viewFile); ?>
    </div>
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']); ?>
    </div>
</div>
