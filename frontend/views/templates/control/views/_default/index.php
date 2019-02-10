<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.12.2018
 * Time: 8:41
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
<div class="index-<?= $templateName; ?>">
    <?php if ($itemsMenu): ?>
        <?php /* Если есть элементы бокового меню */ ?>
        <div class="row">
            <div class="block-left">
                <div class="col-md-3">
                    <div class="row">
                        <?= $this->render('@frontend/views/templates/control/blocks/sidebar/sidebar', [
                            'page' => $page,
                            'modelSearch' => $modelSearch,
                            'dataProvider' => $dataProvider,
                            'itemsMenu' => $itemsMenu,
                            'modelDocumentForm' => $modelDocumentForm,
                            'tree' => $tree,
                            'templateName' => $templateName
                        ]); ?>
                        <?php if ($dataProvider->models && isset($modelSearch->template) && $modelSearch->template->use_filter): ?>
                            <?= $this->render('@frontend/views/templates/control/blocks/search/search', [
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
                </div>
            </div>
            <div class="block-right">
                <div class="col-md-9">
                    <div class="row">
                        <?= $this->render('data', [
                            'page' => $page,
                            'modelSearch' => $modelSearch,
                            'dataProvider' => $dataProvider,
                            'itemsMenu' => $itemsMenu,
                            'modelDocumentForm' => $modelDocumentForm,
                            'tree' => $tree,
                            'templateName' => $templateName
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif (isset($modelSearch->template) && $modelSearch->template->use_filter): ?>
        <?php /* Если есть элементы, но нет бокового меню */ ?>
        <div class="row">
            <div class="block-left">
                <div class="col-md-3">
                    <div class="row">
                        <?= $this->render('@frontend/views/templates/control/blocks/search/search', [
                            'page' => $page,
                            'modelSearch' => $modelSearch,
                            'dataProvider' => $dataProvider,
                            'itemsMenu' => $itemsMenu,
                            'modelDocumentForm' => $modelDocumentForm,
                            'tree' => $tree,
                            'templateName' => $templateName
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="block-right">
                <div class="col-md-9">
                    <div class="row">
                        <?= $this->render('data', [
                            'page' => $page,
                            'modelSearch' => $modelSearch,
                            'dataProvider' => $dataProvider,
                            'itemsMenu' => $itemsMenu,
                            'modelDocumentForm' => $modelDocumentForm,
                            'tree' => $tree,
                            'templateName' => $templateName
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php /* Если нет элементов бокового меню */ ?>
            <?= $this->render('data', [
                'page' => $page,
                'modelSearch' => $modelSearch,
                'dataProvider' => $dataProvider,
                'itemsMenu' => $itemsMenu,
                'modelDocumentForm' => $modelDocumentForm,
                'tree' => $tree,
                'templateName' => $templateName
            ]); ?>
        </div>
    <?php endif; ?>
</div>