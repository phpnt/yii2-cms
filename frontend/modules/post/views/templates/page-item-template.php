<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 08.12.2018
 * Time: 17:57
 */

use common\widgets\Like\Like;

/* @var $this \yii\web\View */
/* @var $page array */
/* @var $item array */
/* @var $itemsMenu array */
?>
<?php if ($itemsMenu): ?>
    <?php /* Если есть меню */ ?>
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']); ?>
    </div>
    <div class="col-md-3">
        <?= $this->render('menu-template', [
            'itemsMenu' => $itemsMenu,
        ]); ?>
    </div>
    <div class="col-md-9">
        <?= $this->render('item-template', [
            'item' => $item,
        ]); ?>
    </div>
<?php else: ?>
    <?php /* Если нет меню */ ?>
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']); ?>
    </div>
    <div class="col-md-12">
        <?= $this->render('item-template', [
            'item' => $item,
        ]); ?>
    </div>
<?php endif; ?>
<div class="col-md-12 text-right">
    <?= Like::widget(['document_id' => $item['id']]) ?>
</div>

