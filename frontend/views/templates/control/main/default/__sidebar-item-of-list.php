<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 15:03
 */

use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $page array */
/* @var $parent array Родительская папка */
/* @var $item array */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

$templateData = $fieldsManage->getData($item['id'], $item['template_id']);
?>
<div class="col-md-12">
    <?php p($this->viewFile); ?>
</div>
<div class="col-xs-12">
    <div class="item-of-list m-b-md">
        <?php /* Отображение элемента в списке */ ?>
        <a href="<?= Url::to(['/control/default/view', 'alias' => $page['alias'], 'parent' => $parent['alias'], 'item_alias' => $item['alias']]) ?>" class="item-link">
            <div class="item-card">
                <div class="text-center p-t-xs">
                    <h3><?= Yii::t('app', $item['name']) ?></h3>
                </div>
                <div>
                    <?php if ($item['template_id']): ?>
                        <?php p($templateData) ?>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    </div>
</div>