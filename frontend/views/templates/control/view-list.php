<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 8:57
 */

use yii\helpers\Url;
use common\widgets\ViewItems\ViewItems;

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $template array используемый шаблон для элементов */
/* @var $item array информация о списке */
?>
<div class="<?= $page['alias']; ?>-view-list">
    <?= ViewItems::widget(['page' => $page, 'template' => $template, 'parent' => $parent]); ?>
</div>