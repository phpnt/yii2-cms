<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 13:14
 */

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $modelSearch \common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $itemsMenu array Элементы меню */
/* @var $modelDocumentForm \common\models\forms\DocumentForm Выбранный элемент */
/* @var $tree array Дерево элемента */
/* @var $templateName string */

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="content-<?= $templateName; ?>">
    <div class="col-md-12">
        <?php p($this->viewFile); ?>
        <?= Yii::t('app', $page['content']); ?>
    </div>
</div>