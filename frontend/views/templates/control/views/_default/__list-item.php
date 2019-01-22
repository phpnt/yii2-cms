<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 13:29
 */

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
?>
<div class="col-md-4 m-b-md">
    <?php p($this->viewFile); ?>
    <div class="list-item-<?= $templateName; ?>">
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