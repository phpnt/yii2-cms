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
use common\widgets\ViewItems\ViewItems;

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $parentItem array родитель элемента */
/* @var $item array информация об элементе */

$this->title = Yii::t('app', $page['title']);

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(['/' . $page['alias'] . '/default/index'])
];
if ($this->title != Yii::t('app', $parentItem['name'])) {
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', $parentItem['name']),
        'url' => Url::to(['/' . $page['alias'] . '/default/view-list', 'folder' => $parentItem['alias']])
    ];
}
$this->params['breadcrumbs'][] = Yii::t('app', $item['name']);
?>
<div class="product-default-view">
    <div class="row">
        <div class="col-md-12">
            <?= ViewItems::widget(['page' => $page, 'selectedPage' => $parentItem, 'selectedItem' => $item]); ?>
        </div>
        <div class="col-md-12 text-right">
            <?= Like::widget(['document_id' => $item['id']]) ?>
        </div>
    </div>
</div>

