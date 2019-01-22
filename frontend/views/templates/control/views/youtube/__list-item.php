<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 13:29
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $parent array Родительская папка */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $items array Элементы в родительской папке */
/* @var $templateName string */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
$templateData = $fieldsManage->getData($item['id'], $item['template_id']);
/* @var $youTubeData \phpnt\youtube\components\YouTubeData */
$youTubeData = Yii::$app->youTubeData;
?>
<div class="col-md-4 m-b-md">
    <div class="list-item-<?= $templateName; ?>">
        <?php /* Отображение элемента в списке */ ?>
        <a href="<?= Url::to(['/control/default/view', 'alias' => $page['alias'], 'parent' => $parent['alias'], 'item_alias' => $item['alias']]) ?>" class="item-link">
            <div class="item-card">
                <div class="header-height">
                    <h3 class="text-center"><?= Yii::t('app', $item['name']) ?></h3>
                </div>
                <?php if ($item['template_id'] && $templateData): ?>
                    <?php if ($url = $fieldsManage->getValueByName('Ссылка', $templateData)): ?>
                        <?php $preview = $youTubeData->getPreview($url, null, 'medium'); ?>
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
