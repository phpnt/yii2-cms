<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 13:40
 */

use yii\helpers\Url;

/* @var $page array Главная страница меню */
/* @var $modelSearch \common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $itemsMenu array Элементы меню */
/* @var $modelDocumentForm \common\models\forms\DocumentForm Выбранный элемент */
/* @var $tree array Дерево элемента */
/* @var $templateName string *//* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
$templateData = $fieldsManage->getData($modelDocumentForm->id, $modelDocumentForm->template_id);

$this->title = Yii::t('app', $page['title']);

// Формируем "хлебные крошки"
foreach ($tree as $value) {
    if ($value['alias'] == $page['alias']) {
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('app', $value['name']),
            'url' => Url::to(['/control/default/index', 'alias_menu_item' => $page['alias']])
        ];
    } elseif ($value['alias'] == $modelDocumentForm->parent->alias) {
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('app', $value['name']),
            'url' => Url::to(['/control/default/view-list', 'alias_menu_item' => $page['alias'], 'alias_sidebar_item' => $value['alias']])
        ];
    } else {
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('app', $value['name']),
        ];
    }
}
$this->params['breadcrumbs'][] = Yii::t('app', $modelDocumentForm->name);
?>
<div class="block-item item-<?= $templateName; ?>">
    <?php if (isset($modelDocumentForm->template->templateViewItem) && $modelDocumentForm->template->templateViewItem->view): ?>
        <?= $modelDocumentForm->dataItem ?>
    <?php else: ?>
        <div class="col-md-12">
            <h1><?= Yii::t('app', $modelDocumentForm->name) ?></h1>
            <?php if ($modelDocumentForm->template_id): ?>
                <?php p($templateData) ?>
            <?php endif; ?>
            <?php if (isset($modelDocumentForm->template->add_rating) && $modelDocumentForm->template->add_rating): ?>
                <div id="rating-widget-<?= $modelDocumentForm->id ?>" class="text-right m-t-lg">
                    <?= \common\widgets\Rating\Rating::widget([
                        'document_id' => $modelDocumentForm->id,
                        'like' => true,             // показывать кнопку "Нравиться"
                        'dislike' => true,          // показывать кнопку "Не нравиться"
                        'percentage' => false,       // показывать процентный рейтинг
                        'stars_number' => 10,       // кол-во звезд в процентном рейтинге (от 2 до 10)
                        'access_guests' => true,    // разрешены не авторизованным пользователям
                    ]) ?>
                </div>
            <?php endif; ?>
            <?php if (isset($modelDocumentForm->template->add_comments) && $modelDocumentForm->template->add_comments): ?>
                <div id="comment-widget">
                    <?= \common\widgets\Comment\Comment::widget([
                        'document_id' => $modelDocumentForm->id,
                        'access_answers' => true,   // разрешены ответы на комментарии
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
