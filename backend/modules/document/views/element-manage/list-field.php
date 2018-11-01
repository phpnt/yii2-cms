<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.09.2018
 * Time: 11:05
 */

/* @var $containerClass string */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
/* @var $attribute string */
/* @var $multiple boolean */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<?php $i = 0; ?>
<div class="<?= $containerClass ?>">
    <?php $modelDocumentForm->$attribute = isset($modelDocumentForm->elements_fields[$modelFieldForm->id][0][$i]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][0][$i] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id); ?>
    <?php if ($multiple == true): ?>
        <?= $form->field($modelDocumentForm, $attribute, [
            'options' => [
                'id' => 'group-' . $modelFieldForm->id . '-' . $i
            ]
        ])->dropDownList($modelFieldForm->list, [
            'id' => 'field-' . $modelFieldForm->id . '-' . $i,
            'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i]",
            'value' => null,
            //'value' => isset($modelDocumentForm->elements_fields[$modelFieldForm->id][0][$i]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][0][$i] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id),
            'class'  => 'form-control selectpicker',
            'multiple' => 'true',
            'data' => [
                'style' => 'btn-default',
                'live-search' => 'false',
                'title' => '---'
            ]
        ])->label(Yii::t('app', $modelFieldForm->name)) ?>
    <?php else: ?>
        <?= $form->field($modelDocumentForm, $attribute, [
            'options' => [
                'id' => 'group-' . $modelFieldForm->id . '-' . $i
            ]
        ])->dropDownList($modelFieldForm->list, [
            'id' => 'field-' . $modelFieldForm->id . '-' . $i,
            'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i]",
            //'value' => isset($modelDocumentForm->elements_fields[$modelFieldForm->id][$i]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][$i] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id),
            'class'  => 'form-control selectpicker',
            'data' => [
                'style' => 'btn-default',
                'live-search' => 'false',
                'title' => '---'
            ]
        ])->label(Yii::t('app', $modelFieldForm->name)) ?>
    <?php endif; ?>
    <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][$i])): ?>
        <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][$i]; ?>
        <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
    <?php endif; ?>
</div>
