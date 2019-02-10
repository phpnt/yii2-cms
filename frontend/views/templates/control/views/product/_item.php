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
    <div class="col-md-12">
        <?php p($this->viewFile); ?>
        <h1><?= Yii::t('app', $modelDocumentForm->name) ?></h1>
        <?php if ($modelDocumentForm->template_id): ?>
            <?php p($templateData) ?>
        <?php endif; ?>
    </div>
</div>
