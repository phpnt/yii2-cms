<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 14:12
 */

use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $item array Выбранный элемент */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

$this->title = Yii::t('app', $item['name']);
if ($page['alias'] != 'main') {
    $this->params['breadcrumbs'][] = [
        'label' => $page['title'],
        'url' => Url::to(['/control/default/index', 'alias' => $page['alias']])
    ];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <?php p($this->viewFile); ?>
</div>
<div class="col-md-12">
    <div class="selected-item">
        <div class="row">
            <?php if ($template['mark'] == 'default' || $template['mark'] == ''): ?>
                <div class="col-md-12">
                    <div class="row">
                        <?= $this->render('default/_one-item', [
                            'page' => $page,
                            'item' => $item,
                        ]); ?>
                    </div>
                </div>
            <?php elseif ($template['mark'] == 'youtube'): ?>
                <div class="col-md-12">
                    <div class="row">
                        <?= $this->render('youtube/_one-item', [
                            'page' => $page,
                            'item' => $item,
                        ]); ?>
                    </div>
                </div>
            <?php elseif ($template['mark'] == 'article'): ?>
                <div class="col-md-12">
                    <div class="row">
                        <?= $this->render('article/_one-item', [
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
