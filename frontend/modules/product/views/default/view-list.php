<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 8:57
 */

use yii\helpers\Url;
use common\widgets\ViewItems\ViewItems;

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $selectedPage array информация о списке */

$this->title = Yii::t('app', $selectedPage['name']);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app',  $page['name']),
    'url' => Url::to(['/' . $page['alias'] . '/default/index'])
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-default-view-list">
    <?= ViewItems::widget(['page' => $page, 'selectedPage' => $selectedPage]); ?>
</div>