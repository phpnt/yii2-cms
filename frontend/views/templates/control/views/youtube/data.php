<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 12:20
 */

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $modelSearch \common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $itemsMenu array Элементы меню */
/* @var $modelDocumentForm \common\models\forms\DocumentForm Выбранный элемент */
/* @var $tree array Дерево элемента */
/* @var $templateName string */
?>
<div class="data-<?= $templateName; ?>">
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
