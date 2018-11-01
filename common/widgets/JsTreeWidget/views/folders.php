<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 21.09.2018
 * Time: 11:09
 */

/* @var $widget \common\widgets\JsTreeWidget\JsTreeWidget */
/* @var $this \yii\web\View */
?>
<div id="<?= $widget->id ?>">
    <?php if (!$widget->items): ?>
        <?= Yii::t('app', 'Отсутствуюет массив элементов "items".') ?>
    <?php endif; ?>
</div>