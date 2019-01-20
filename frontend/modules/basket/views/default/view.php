<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 17:16
 */

use yii\helpers\Url;
use common\widgets\Like\Rating;

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $item array информация об элементе */

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(['/' . $page['alias'] . '/default/index'])
];
$this->params['breadcrumbs'][] = Yii::t('app', $item['name']);
?>
<div class="post-default-view">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render('@frontend/modules/'. $page['alias'] .'/views/templates/item-template', [
                'page' => $page,
                'item' => $item,
            ]); ?>
        </div>
        <div class="col-md-12 text-right">
            <?= Rating::widget(['document_id' => $item['id']]) ?>
        </div>
    </div>
</div>

