<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 17:16
 */

use common\widgets\ViewItems\ViewItems;
use common\widgets\Like\Like;
use common\widgets\Basket\BasketButton;

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array родитель элемента */
/* @var $item array информация об элементе */
?>
<div class="<?= $page['alias']; ?>-view">
    <?= ViewItems::widget(['page' => $page, 'template' => $template, 'parent' => $parent, 'item' => $item]); ?>
    <?php if ($page['alias'] == 'product'): ?>
        <div class="col-md-12 text-right">
            <?= Like::widget(['document_id' => $item['id']]) ?>
        </div>
        <div class="col-md-12 text-right">
            <?= BasketButton::widget(['document_id' => $item['id']]) ?>
        </div>
    <?php else: ?>
        <div class="col-md-12 text-right">
            <?= Like::widget(['document_id' => $item['id']]) ?>
        </div>
    <?php endif; ?>
</div>

