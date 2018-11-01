<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.09.2018
 * Time: 11:19
 */

use common\models\Constants;

/* @var $containerClass string */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
/* @var $attribute string */
/* @var $attribute2 string */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<?php $i = 0; ?>
<?php if ($modelFieldForm->type == Constants::FIELD_TYPE_DATE_RANGE): ?>
    <div class="<?= $containerClass ?>">
        <?php if ($data = $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id)): ?>
            <?php $modelDocumentForm->$attribute = $data[0]; ?>
            <?php $modelDocumentForm->$attribute2 = $data[1]; ?>
        <?php else: ?>
            <?php if (isset($modelDocumentForm->elements_fields[$modelFieldForm->id][0])): ?>
                <?php $modelDocumentForm->$attribute = $modelDocumentForm->elements_fields[$modelFieldForm->id][0]; ?>
            <?php endif; ?>
            <?php if (isset($modelDocumentForm->elements_fields[$modelFieldForm->id][1])): ?>
                <?php $modelDocumentForm->$attribute2 = $modelDocumentForm->elements_fields[$modelFieldForm->id][1]; ?>
            <?php endif; ?>
        <?php endif; ?>
        <?= $form->field($modelDocumentForm, $attribute, [
            'options' => [
                'id' => 'group-' . $modelFieldForm->id . '-' . $i
            ]
        ])->widget(\phpnt\datepicker\BootstrapDatepicker::className(), [
            'type' => \phpnt\datepicker\BootstrapDatepicker::TYPE_RANGE,
            'attribute_2' => $attribute2,
            'autoclose' => true,
            'format' => 'dd.mm.yyyy',
            'language'  => Yii::$app->language,
            'options' => [
                'class' => 'form-control',
                'name' => "DocumentForm[elements_fields][$modelFieldForm->id][]",
            ]
        ])->label(Yii::t('app', $modelFieldForm->name)) ?>
        <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][0])): ?>
            <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][0]; ?>
            <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
        <?php endif; ?>
        <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][1])): ?>
            <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][1]; ?>
            <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="<?= $containerClass ?>">
        <?= $form->field($modelDocumentForm, $attribute, [
            'options' => [
                'id' => 'group-' . $modelFieldForm->id . '-' . $i
            ]
        ])->widget(\phpnt\datepicker\BootstrapDatepicker::className(), [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy',
            'language'  => Yii::$app->language,
            'options' => [
                'id' => 'field-' . $modelFieldForm->id . '-' . $i,
                'class' => 'form-control',
                'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i]",
                //'value' => isset($modelDocumentForm->elements_fields[$modelFieldForm->id][$i]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][$i] : null,
                'value' => $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id),
            ]
        ])->label(Yii::t('app', $modelFieldForm->name)) ?>
        <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][$i])): ?>
            <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][$i]; ?>
            <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
        <?php endif; ?>
    </div>
<?php endif; ?>