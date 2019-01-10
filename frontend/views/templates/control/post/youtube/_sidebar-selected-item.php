<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 15:40
 */

use common\models\Constants;
use frontend\views\templates\control\post\youtube\assets\YoutubeTempAsset;
use phpnt\youtube\YouTubeWidget;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $parent array Родительская папка */
/* @var $item array Выбранный элемент */
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
<div class="col-md-12">
    <div class="row">
        <div class="item-youtube sidebar-selected-item-content">
            <div class="col-md-12 text-center">
                <h1><?= Yii::t('app', $item['name']) ?></h1>
            </div>
            <?php if ($item['template_id']): ?>
                <?php foreach ($templateData as $field): ?>
                    <?php if ($field['type'] == Constants::FIELD_TYPE_YOUTUBE): ?>
                        <div class="col-md-12 text-center">
                            <?= YouTubeWidget::widget(['video_link' => $field['value']]); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($item['template_id']): ?>
                <?php foreach ($templateData as $field): ?>
                    <?php if ($field['title'] == 'Автор'): ?>
                        <div class="col-md-12 text-center p-t-md p-b-sm">
                            <h4><?= Yii::t('app', 'Автор'); ?>: <?= Yii::t('app', $field['value']); ?></h4>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php /*p($templateData) */?>
            <?php endif; ?>
        </div>
    </div>
</div>
