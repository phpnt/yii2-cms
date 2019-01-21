<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 17:16
 */

use common\widgets\ViewItems\ViewItems;
use common\widgets\Rating\Rating;
use common\widgets\Comment\Comment;
use common\widgets\Basket\BasketButton;

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array родитель элемента */
/* @var $item array информация об элементе */
?>
<div class="<?= $page['alias']; ?>-view">
    <?= ViewItems::widget(['page' => $page, 'template' => $template, 'parent' => $parent, 'item' => $item]); ?>
    <?php if ($template['add_rating']): ?>
        <div class="col-md-12 text-right">
            <?= Rating::widget([
                'document_id' => $item['id'],
                'like' => true,             // показывать кнопку "Нравиться"
                'dislike' => true,          // показывать кнопку "Не нравиться"
                'percentage' => true,       // показывать процентный рейтинг
                'stars_number' => 10,         // кол-во звезд в процентном рейтинге (от 2 до 10)
                'access_guests' => true,    // разрешены не авторизованным пользователям
            ]) ?>
        </div>
    <?php endif; ?>
    <?php if ($template['add_comments']): ?>
        <?= Comment::widget([
            'document_id' => $item['id'],
            'access_answers' => true,   // разрешены ответы на комментарии
        ]) ?>
    <?php endif; ?>
</div>

