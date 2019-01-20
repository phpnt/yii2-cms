<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.01.2019
 * Time: 12:32
 */

/* @var $this yii\web\View */
/* @var $countComment */
?>
<?php if ($countComment): ?>
    <span class="pull-right-container">
        <span class="label label-danger pull-right"><?= $countComment ?></span>
    </span>
<?php endif; ?>
