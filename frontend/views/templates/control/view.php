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
/* @var $alias_menu_item string алиас элемента главного меню */
/* @var $alias_sidebar_item string алиас элемента бокового меню */
/* @var $modelDocumentForm \common\models\forms\DocumentForm выбранный элемент */
?>
<div class="<?= $alias_menu_item; ?>-view">
    <?= ViewItems::widget(['alias_menu_item' => $alias_menu_item, 'alias_sidebar_item' => $alias_sidebar_item, 'modelDocumentForm' => $modelDocumentForm]); ?>
    <?php if (isset($modelDocumentForm->template->add_rating) && $modelDocumentForm->template->add_rating): ?>
        <div class="col-md-12 text-right">
            <?= Rating::widget([
                'document_id' => $modelDocumentForm->id,
                'like' => true,             // показывать кнопку "Нравиться"
                'dislike' => true,          // показывать кнопку "Не нравиться"
                'percentage' => true,       // показывать процентный рейтинг
                'stars_number' => 10,       // кол-во звезд в процентном рейтинге (от 2 до 10)
                'access_guests' => true,    // разрешены не авторизованным пользователям
            ]) ?>
        </div>
    <?php endif; ?>
    <?php if (isset($modelDocumentForm->template->add_comments) && $modelDocumentForm->template->add_comments): ?>
        <?= Comment::widget([
            'document_id' => $modelDocumentForm->id,
            'access_answers' => true,   // разрешены ответы на комментарии
        ]) ?>
    <?php endif; ?>
</div>

