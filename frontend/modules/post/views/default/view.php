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

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $dataItem array информация об элементе */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(['/' . $page['alias'] . '/default/index'])
];
$this->params['breadcrumbs'][] = Yii::t('app', $dataItem['name']);
?>
<div class="post-default-view">
    <div class="col-md-12">
        <?= Yii::t('app', $dataItem['name']) ?><br>
        <?= Yii::t('app', $dataItem['annotation']) ?><br>
        <?= Yii::t('app', $dataItem['content']) ?><br>
        <?php if ($page['template_id']): ?>
            <?php $templateData = $fieldsManage->getData($dataItem['id'], $page['template_id']) ?>
            <?php p($templateData) ?>
        <?php endif; ?>
    </div>
    <div class="col-md-12 text-right">
        <?= Like::widget(['document_id' => $dataItem['id']]) ?>
    </div>
</div>

