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
/* @var $fieldsManage \common\components\other\FieldsManage */
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
            <?php if ($template['mark'] == 'video-youtube'): ?>
                <?= $this->render('tempYoutube/_one-item', [
                    'page' => $page,
                    'item' => $item,
                ]); ?>
            <?php elseif ($template['mark'] == 'article'): ?>
                <?= $this->render('tempArticle/_one-item', [
                    'page' => $page,
                    'item' => $item,
                ]); ?>
            <?php elseif ($template['mark'] == 'product'): ?>
                <?= $this->render('tempProduct/_one-item', [
                    'page' => $page,
                    'item' => $item,
                ]); ?>
            <?php else: ?>
                <?= $this->render('_one-item', [
                    'page' => $page,
                    'item' => $item,
                ]); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
