<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 14.02.2019
 * Time: 11:56
 */

use common\models\Constants;
use yii\helpers\Html;
use yii\helpers\Url;
use phpnt\bootstrapNotify\BootstrapNotify;
/* @var $this yii\web\View */
/* @var $modelTemplateForm \common\models\forms\TemplateForm */
?>
<?= BootstrapNotify::widget() ?>
<?php if (Yii::$app->user->can('document/field-manage/create-field')): ?>
    <?= Html::button('<i class="fas fa-file-alt"></i>', [
        'class' => isset($modelTemplateForm->templateViewItem) ? 'btn btn-xs btn-primary' : 'btn btn-xs btn-success',
        'title' => Yii::t('app', 'Страница элемента'),
        'onclick' => '
            $.pjax({
                type: "GET",
                url: "' . Url::to(['/document/template-view-manage/index', 'template_id' => $modelTemplateForm->id, 'type' => Constants::TYPE_ITEM]) . '", 
                container: "#pjaxModalUniversal",
                push: false,
                timeout: 10000,
                scrollTo: false
            })'
    ]); ?>
    <?= Html::button('<i class="fas fa-list-ul"></i>', [
        'class' => isset($modelTemplateForm->templateViewItemList) ? 'btn btn-xs btn-primary' : 'btn btn-xs btn-success',
        'title' => Yii::t('app', 'Элемент в списке'),
        'onclick' => '
            $.pjax({
                type: "GET",
                url: "' . Url::to(['/document/template-view-manage/index', 'template_id' => $modelTemplateForm->id, 'type' => Constants::TYPE_ITEM_LIST]) . '", 
                container: "#pjaxModalUniversal",
                push: false,
                timeout: 10000,
                scrollTo: false
            })'
    ]); ?>
    <?php foreach ($modelTemplateForm->fields as $field): ?>
        <?php if ($field->type == Constants::FIELD_TYPE_PRICE): ?>
            <?= Html::button('<i class="fas fa-shopping-cart"></i>', [
                'class' => isset($modelTemplateForm->templateViewItemBasket) ? 'btn btn-xs btn-primary' : 'btn btn-xs btn-success',
                'title' => Yii::t('app', 'Элемент в корзине'),
                'onclick' => '
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['/document/template-view-manage/index', 'template_id' => $modelTemplateForm->id, 'type' => Constants::TYPE_ITEM_BASKET]) . '", 
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })'
            ]); ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <?= Yii::t('app', 'Нет доступа') ?>
<?php endif; ?>
