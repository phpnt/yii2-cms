<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 11:28
 */

use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $page array */
/* @var $item array */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<div class="col-xs-12">
    <?php p($this->viewFile); ?>
</div>
<div class="col-xs-12">
    <div class="item-of-list">
        <?php /* Отображение элемента в списке */ ?>
        <a href="<?= Url::to(['/control/default/view', 'alias' => $page['alias'], 'parent' => $page['alias'], 'item_alias' => $item['alias']]) ?>" class="element-link">
            <div class="element-card">
                <?= Yii::t('app', $item['name']) ?><br>
                <?= Yii::t('app', $item['annotation']) ?><br>
                <?= Yii::t('app', $item['content']) ?><br>
                <?php if ($item['template_id']): ?>
                    <?php $templateData = $fieldsManage->getData($item['id'], $item['template_id']) ?>
                    <?php p($templateData) ?>
                <?php endif; ?>
            </div>
        </a>
    </div>
</div>