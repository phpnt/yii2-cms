<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 13:40
 */

use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */
/* @var $tree array Дерево элемента */
/* @var $templateName string */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
$templateData = $fieldsManage->getData($item['id'], $item['template_id']);

$this->title = Yii::t('app', $page['title']);

// Формируем "хлебные крошки"
foreach ($tree as $value) {
    if ($value['alias'] == $page['alias']) {
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('app', $value['name']),
            'url' => Url::to(['/control/default/index', 'alias' => $page['alias']])
        ];
    } elseif ($value['alias'] == $parent['alias']) {
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('app', $value['name']),
            'url' => Url::to(['/control/default/view-list', 'alias' => $page['alias'], 'folder_alias' => $value['alias']])
        ];
    } else {
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('app', $value['name']),
        ];
    }
}
$this->params['breadcrumbs'][] = Yii::t('app', $item['name']);
?>
<div class="col-md-12">
    <div class="item-<?= $templateName; ?>">
        <?php p($this->viewFile); ?>
        <h1><?= Yii::t('app', $item['name']) ?></h1>
        <?php if ($item['template_id']): ?>
            <?php p($templateData) ?>
        <?php endif; ?>
    </div>
</div>
