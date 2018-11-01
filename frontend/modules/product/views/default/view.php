<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 17:16
 */

use yii\helpers\Url;
use common\widgets\Like\Like;
use common\widgets\Basket\BasketButton;
use common\widgets\NavMenu\NavMenu;
use common\models\Constants;

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $data array информация о списке */
/* @var $dataItem array информация об элементе */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(['/' . $page['alias'] . '/default/index'])
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $data['name']),
    'url' => Url::to(['/' . $page['alias'] . '/default/view-list', 'folder' => $data['alias']])
];
$this->params['breadcrumbs'][] = Yii::t('app', $dataItem['name']);
?>
<div class="post-default-view">
    <div class="col-md-3 m-b-lg">
        <?= NavMenu::widget(['folder' => $page['alias'], 'document_id' => $page['id']]); ?>
    </div>
    <div class="col-md-9">
        <?= Yii::t('app', $dataItem['name']) ?><br>
        <?= Yii::t('app', $dataItem['annotation']) ?><br>
        <?= Yii::t('app', $dataItem['content']) ?><br>
        <?php if ($dataItem['template_id']): ?>
            <?php $templateData = $fieldsManage->getData($dataItem['id'], $dataItem['template_id']) ?>
            <?php p($templateData) ?>
        <?php endif; ?>
    </div>
    <div class="col-md-12 text-right">
        <?= Like::widget(['document_id' => $dataItem['id']]) ?>
    </div>
    <div class="col-md-12 text-right">
        <?= BasketButton::widget(['document_id' => $dataItem['id']]) ?>
    </div>
</div>
