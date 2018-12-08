<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 08.12.2018
 * Time: 17:04
 */

/* @var $this \yii\web\View */
/* @var $page array */
/* @var $item array */
/* @var $itemsMenu array */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<?php if ($itemsMenu): ?>
    <?php /* Если есть меню */ ?>
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']); ?>
    </div>
    <div class="col-md-3">
        <?= $this->render('menu-template', [
            'itemsMenu' => $itemsMenu,
        ]); ?>
    </div>
    <div class="col-md-9">
        <div class="row">
            <?php foreach ($items as $item): ?>
                <div class="col-md-3">
                    <?= $this->render('list-item-template', [
                        'page' => $page,
                        'item' => $item,
                    ]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <?php /* Если нет меню */ ?>
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']); ?>
    </div>
    <?php foreach ($items as $item): ?>
        <div class="col-md-3">
            <?= $this->render('list-item-template', [
                'page' => $page,
                'item' => $item,
            ]); ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

