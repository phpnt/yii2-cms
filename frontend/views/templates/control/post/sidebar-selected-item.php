<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 15:07
 */

use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */

$this->title = Yii::t('app', $page['title']);

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(['/' . $page['alias'] . '/default/index'])
];
if ($this->title != Yii::t('app', $parent['name'])) {
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', $parent['name']),
        'url' => Url::to(['/control/default/view-list', 'alias' => $page['alias'], 'folder_alias' => $parent['alias']])
    ];
}
$this->params['breadcrumbs'][] = Yii::t('app', $item['name']);
?>
<div class="sidebar-selected-item-content">
    <div class="col-md-12">
        <?php p($this->viewFile); ?>
    </div>
    <div class="col-md-3">
        <div class="row">
            <?= $this->render('sidebar-menu', [
                'page' => $page,
                'itemsMenu' => $itemsMenu,
            ]); ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <?php if ($template['mark'] == 'default' || $template['mark'] == ''): ?>
                <div class="col-md-12">
                    <div class="row">
                        <?= $this->render('default/_sidebar-selected-item', [
                            'page' => $page,
                            'item' => $item,
                        ]); ?>
                    </div>
                </div>
            <?php elseif ($template['mark'] == 'youtube'): ?>
                <div class="col-md-12">
                    <div class="row">
                        <?= $this->render('youtube/_sidebar-selected-item', [
                            'page' => $page,
                            'item' => $item,
                        ]); ?>
                    </div>
                </div>
            <?php elseif ($template['mark'] == 'article'): ?>
                <div class="col-md-12">
                    <div class="row">
                        <?= $this->render('article/_sidebar-selected-item', [
                            'page' => $page,
                            'item' => $item,
                        ]); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-12">
                    <?= Yii::t('app', 'Необходимо создать новое представление для выбранного элемента шаблона {temp}.', ['temp' => $template['mark']]); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

