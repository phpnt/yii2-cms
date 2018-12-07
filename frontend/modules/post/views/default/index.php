<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use common\widgets\ViewItems\ViewItems;

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $dataItems array элементы страницы */

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-default-index">
    <div class="row">
        <div class="col-md-12">
            <?= Yii::t('app', $page['content']); ?>
        </div>
        <div class="col-md-12">
            <?= ViewItems::widget(['page' => $page]); ?>
        </div>
    </div>
</div>
