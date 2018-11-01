<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 24.09.2018
 * Time: 9:06
 */

use yii\bootstrap\Html;
use yii\helpers\Url;
use phpnt\bootstrapNotify\BootstrapNotify;
use common\models\Constants;

/* @var $this yii\web\View */
/* @var $manyFieldForm array */
/* @var $modelFieldForm \common\models\forms\FieldForm */
/* @var $key int */
/* @var $index int */
/* @var $column yii\grid\DataColumn */
?>
<div id="field_of_template_<?= $key ?>">
    <?= BootstrapNotify::widget() ?>
    <table class="table table-hover">
        <tbody>
        <?php foreach ($manyFieldForm as $modelFieldForm): ?>
            <tr>
                <td class="text-center vcenter" style="max-width: 65px !important; width: 65px !important;">
                    <?php if (Yii::$app->user->can('document/field-manage/view-field')): ?>
                        <?= Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0);', [
                            'class' => 'text-info',
                            'title' => Yii::t('app', 'Просмотр поля'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/document/field-manage/view-field', 'id' => $modelFieldForm->id]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                        ]); ?>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can('document/field-manage/update-field')): ?>
                        <?= Html::a('<i class="fa fa-pen"></i>', 'javascript:void(0);', [
                            'class' => 'text-warning',
                            'title' => Yii::t('app', 'Изменить поле'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/document/field-manage/update-field', 'id' => $modelFieldForm->id]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                        ]); ?>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can('document/field-manage/confirm-delete-field')): ?>
                        <?= Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                            'class' => 'text-danger',
                            'title' => Yii::t('app', 'Удалить поле'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['field-manage/confirm-delete-field', 'id' => $modelFieldForm->id]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                        ]); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?= $modelFieldForm->name ?> (<?= $modelFieldForm->typeItem ?>)<br>
                    <?php if (isset($modelFieldForm->valueStringsOfTemplate)): ?>
                        <?php foreach ($modelFieldForm->valueStringsOfTemplate as $modelValueStringForm): ?>
                            <?php /* @var $modelValueStringForm \common\models\forms\ValueStringForm */ ?>
                            <?= $modelValueStringForm->value ?><br>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_DATE || $modelFieldForm->type == Constants::FIELD_TYPE_DATE_RANGE): ?>
                        <?php if ($modelFieldForm->min): ?>
                            <?= Yii::$app->formatter->asDate($modelFieldForm->min) ?>
                        <?php endif; ?>
                        <?= ' - ' ?>
                        <?php if ($modelFieldForm->max): ?>
                            <?= Yii::$app->formatter->asDate($modelFieldForm->max) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


