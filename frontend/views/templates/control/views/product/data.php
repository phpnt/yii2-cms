<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 12:20
 */

use common\widgets\Rating\Rating;
use common\widgets\Comment\Comment;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $modelSearch \common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $itemsMenu array Элементы меню */
/* @var $modelDocumentForm \common\models\forms\DocumentForm Выбранный элемент */
/* @var $tree array Дерево элемента */
/* @var $templateName string */
?>
<div class="block-data data-<?= $templateName; ?>">
    <?php if ($modelDocumentForm): ?>
        <?php /* Если выбран элемент из списка */ ?>
        <?= $this->render('_item', [
            'page' => $page,
            'modelSearch' => $modelSearch,
            'dataProvider' => $dataProvider,
            'itemsMenu' => $itemsMenu,
            'modelDocumentForm' => $modelDocumentForm,
            'tree' => $tree,
            'templateName' => $templateName
        ]); ?>
        <?php if (isset($modelDocumentForm->template->add_rating) && $modelDocumentForm->template->add_rating): ?>
            <div id="rating-widget">
                <?= Rating::widget([
                    'document_id' => $modelDocumentForm->id,
                    'like' => false,             // показывать кнопку "Нравиться"
                    'dislike' => false,          // показывать кнопку "Не нравиться"
                    'percentage' => true,       // показывать процентный рейтинг
                    'stars_number' => 10,       // кол-во звезд в процентном рейтинге (от 2 до 10)
                    'access_guests' => true,    // разрешены не авторизованным пользователям
                ]) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($modelDocumentForm->template->add_comments) && $modelDocumentForm->template->add_comments): ?>
            <div id="comment-widget">
                <?= Comment::widget([
                    'document_id' => $modelDocumentForm->id,
                    'access_answers' => true,   // разрешены ответы на комментарии
                ]) ?>
            </div>
        <?php endif; ?>
    <?php elseif ($dataProvider->models): ?>
        <?php /* Если отображается список */ ?>
        <?= $this->render('_list', [
            'page' => $page,
            'modelSearch' => $modelSearch,
            'dataProvider' => $dataProvider,
            'itemsMenu' => $itemsMenu,
            'modelDocumentForm' => $modelDocumentForm,
            'tree' => $tree,
            'templateName' => $templateName
        ]); ?>
    <?php else: ?>
        <?php /* Если нет ни элемента, ни списка */ ?>
        <?= $this->render('_content', [
            'page' => $page,
            'modelSearch' => $modelSearch,
            'dataProvider' => $dataProvider,
            'itemsMenu' => $itemsMenu,
            'modelDocumentForm' => $modelDocumentForm,
            'tree' => $tree,
            'templateName' => $templateName
        ]); ?>
    <?php endif; ?>
</div>
