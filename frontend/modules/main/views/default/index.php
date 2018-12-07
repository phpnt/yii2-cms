<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

/* @var $this yii\web\View */
/* @var $page array информация о странице */

$this->title = Yii::t('app', $page['title']);
?>
<div class="main-default-index">
    <div class="row">
        <div class="col-md-12">
            <?= Yii::t('app', $page['content']); ?>
        </div>
    </div>
</div>
