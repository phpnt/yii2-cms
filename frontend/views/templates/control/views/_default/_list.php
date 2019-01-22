<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 13:21
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
$this->params['breadcrumbs'][] = Yii::t('app', $parent['name']);
?>
<div class="col-md-9">
    <div class="row">
        <div class="col-md-12">
            <div class="list-<?= $templateName; ?>">
                <?php p($this->viewFile); ?>
                <div class="row">
                    <?php foreach ($items as $item): ?>
                        <?= $this->render('__list-item', [
                            'page' => $page,
                            'template' => $template,
                            'parent' => $parent,
                            'itemsMenu' => $itemsMenu,
                            'item' => $item,
                            'items' => $items,
                            'templateName' => $templateName
                        ]); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
