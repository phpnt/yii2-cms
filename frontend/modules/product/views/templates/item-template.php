<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.12.2018
 * Time: 8:47
 */

/* @var $this \yii\web\View */
/* @var $page array */
/* @var $item array */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<?= Yii::t('app', $item['name']) ?><br>
<?= Yii::t('app', $item['annotation']) ?><br>
<?= Yii::t('app', $item['content']) ?><br>
<?php if ($item['template_id']): ?>
    <?php $templateData = $fieldsManage->getData($item['id'], $item['template_id']) ?>
    <?php p($templateData) ?>
<?php endif; ?>