<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.12.2018
 * Time: 8:41
 */

use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $page array */
/* @var $item array */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<a href="<?= Url::to(['/' . $page['alias'] . '/default/view', 'alias' => $item['alias']]) ?>" class="element-link">
    <div class="element-card">
        <?= Yii::t('app', $item['name']) ?><br>
        <?= Yii::t('app', $item['annotation']) ?><br>
        <?= Yii::t('app', $item['content']) ?><br>
        <?= Yii::t('app', 'Количество') ?>: <?= $item['quantity'] ?><br>
        <?php if ($item['template_id']): ?>
            <?php $templateData = $fieldsManage->getData($item['id'], $item['template_id']) ?>
            <?php p($templateData) ?>
        <?php endif; ?>
    </div>
</a>
