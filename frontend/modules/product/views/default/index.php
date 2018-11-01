<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use common\widgets\NavMenu\NavMenu;

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $dataItems array элементы страницы */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-default-index">
    <div class="col-md-3 m-b-lg">
        <?= NavMenu::widget(['folder' => $page['alias'], 'document_id' => $page['id']]); ?>
    </div>
    <div class="col-md-9">
        <?= Yii::t('app', $page['name']) ?>
        <?= Yii::t('app', $page['content']) ?>
    </div>
</div>
