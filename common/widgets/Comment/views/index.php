<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.01.2019
 * Time: 12:32
 */

use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $countComment */
?>
<span id="unchecked-count-comments">
    <?= BootstrapNotify::widget() ?>
    <?php if ($countComment): ?>
        <span class="pull-right-container">
        <span class="label label-warning pull-right"><?= $countComment ?></span>
    </span>
    <?php endif; ?>
</span>
