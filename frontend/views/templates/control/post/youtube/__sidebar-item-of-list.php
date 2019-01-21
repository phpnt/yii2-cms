<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 15:03
 */

use yii\helpers\Url;
use yii\bootstrap\Html;
use common\models\Constants;
use frontend\views\templates\control\post\youtube\assets\YoutubeTempAsset;

/* @var $this \yii\web\View */
/* @var $page array */
/* @var $parent array Родительская папка */
/* @var $item array */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
/* @var $youTubeData \phpnt\youtube\components\YouTubeData */
$youTubeData = Yii::$app->youTubeData;
$templateData = $fieldsManage->getData($item['id'], $item['template_id']);
YoutubeTempAsset::register($this);
?>
<div class="col-md-12">
    <?php p($this->viewFile); ?>
</div>
<div class="col-xs-12">
    <div class="item-youtube item-of-list m-b-md">
        <?php /* Отображение элемента в списке */ ?>
        <a href="<?= Url::to(['/control/default/view', 'alias' => $page['alias'], 'parent' => $parent['alias'], 'item_alias' => $item['alias']]) ?>" class="item-link">
            <div class="item-card">
                <?php if ($item['template_id']): ?>
                    <?php foreach ($templateData as $field): ?>
                        <?php if ($field['type'] == Constants::FIELD_TYPE_YOUTUBE): ?>
                            <?php $preview = $youTubeData->getPreview($field['value']); ?>
                            <?= Html::img($preview['url'], [
                                'class' => 'full-width'
                            ]); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="text-center p-t-xs">
                    <?= Yii::t('app', $item['name']) ?>
                </div>
                <?php if ($item['template_id']): ?>
                    <?php foreach ($templateData as $field): ?>
                        <?php if ($field['title'] == 'Автор'): ?>
                            <div class="text-center m-t-xs p-b-sm">
                                <?= Yii::t('app', 'Автор'); ?>: <?= Yii::t('app', $field['value']); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php /*p($templateData) */?>
                <?php endif; ?>
            </div>
        </a>
    </div>
</div>