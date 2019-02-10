<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 13:29
 */

use yii\helpers\Url;
use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $key int */
/* @var $index int */
/* @var $widget \yii\widgets\ListView */
/* @var $templateName string */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
$templateData = $fieldsManage->getData($modelDocumentForm->id, $modelDocumentForm->template_id);

$templateName = $modelDocumentForm->template ? $modelDocumentForm->template->mark : 'default';

/* @var $youTubeData \phpnt\youtube\components\YouTubeData */
$youTubeData = Yii::$app->youTubeData;

if ($modelDocumentForm->alias_menu_item ==$modelDocumentForm->parent->alias) {
    $url = Url::to(['/control/default/view', 'alias_menu_item' => $modelDocumentForm->alias_menu_item, 'alias_item' => $modelDocumentForm->alias]);
} else {
    $url = Url::to(['/control/default/view', 'alias_menu_item' => $modelDocumentForm->alias_menu_item, 'alias_sidebar_item' => $modelDocumentForm->parent->alias, 'alias_item' => $modelDocumentForm->alias]);
}
?>
<div class="list-item-<?= $templateName; ?>">
    <div class="col-md-4 m-b-md">
        <?php /* Отображение элемента в списке */ ?>
        <a href="<?= $url ?>" class="item-link">
            <div class="item-card">
                <div class="header-height">
                    <h3 class="text-center"><?= Yii::t('app', $modelDocumentForm->name) ?></h3>
                </div>
                <?php if ($modelDocumentForm->template_id): ?>
                    <?php if ($previewUrl = $fieldsManage->getValueByName('Ссылка', $templateData)): ?>
                        <?php $preview = $youTubeData->getPreview($previewUrl, null, 'medium'); ?>
                        <?= Html::img($preview['url'], [
                            'class' => 'full-width'
                        ]); ?>
                    <?php endif; ?>
                    <?php if ($author = $fieldsManage->getValueByName('Автор', $templateData)): ?>
                        <h4 class="text-center"><?= $author ?></h4>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </a>
    </div>
</div>
