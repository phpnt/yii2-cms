<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.08.2018
 * Time: 10:57
 */

/* @var $widget \common\widgets\JsTreeWidget\JsTreeWidget */
/* @var $this \yii\web\View */
?>
<div id="<?= $widget->id ?>">
    <?php if (!$widget->getRootUrl && !$widget->getChildUrl): ?>
        <?= Yii::t('app', 'Отсутствуют обязательные свойства "getRootUrl" или "getChildUrl" или массив элементов "items".') ?>
    <?php endif; ?>
</div>
