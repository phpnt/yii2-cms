<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $items array элементы страницы */

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-default-index">
    <div class="row">
        <div class="col-md-12 m-b-lg">
            <?= Yii::t('app', $page['content']); ?>
        </div>
        <?php foreach ($items as $item): ?>
            <div class="col-md-4">
                <?= $this->render('@frontend/modules/'. $page['alias'] .'/views/templates/list-item-template', [
                    'page' => $page,
                    'item' => $item,
                ]); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
