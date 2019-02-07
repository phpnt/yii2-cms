<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 8:42
 */

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $modelSearch \common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $itemsMenu array Элементы меню */
/* @var $modelDocumentForm \common\models\forms\DocumentForm Выбранный элемент */
/* @var $tree array Дерево элемента */

$templateName = $modelSearch->template ? $modelSearch->template->mark : 'default';
?>
<?php /* Если корневая папка основного меню, без бокового меню и без элементов в папке. */ ?>
<?php $file = Yii::getAlias( '@frontend/views/templates/control/views/' . $templateName . '/index.php'); ?>
<?php /* Если шаблон существует, выводим его, если нет, то выводим шаблон по умолчанию */ ?>
<?php if(file_exists($file)): ?>
    <?= $this->render($templateName . '/index', [
        'page' => $page,
        'modelSearch' => $modelSearch,
        'dataProvider' => $dataProvider,
        'itemsMenu' => $itemsMenu,
        'modelDocumentForm' => $modelDocumentForm,
        'tree' => $tree,
        'templateName' => $templateName
    ]); ?>
<?php else: ?>
    <?= $this->render('_default/index', [
        'page' => $page,
        'modelSearch' => $modelSearch,
        'dataProvider' => $dataProvider,
        'itemsMenu' => $itemsMenu,
        'modelDocumentForm' => $modelDocumentForm,
        'tree' => $tree,
        'templateName' => $templateName
    ]); ?>
<?php endif; ?>
