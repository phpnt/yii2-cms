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
/* @var $model \common\models\forms\DocumentForm */
/* @var $modelName string */
/* @var $modelFieldForm \common\models\forms\FieldForm */
/* @var $attribute string */
/* @var $attribute2 string */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<?php $i = 0; ?>
<?php if ($modelFieldForm->type == Constants::FIELD_TYPE_DATE_RANGE): ?>
    <div class="<?= $containerClass ?>">
        <?php if ($data = $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $model->id)): ?>
            <?php $model->$attribute = $data[0]; ?>
            <?php $model->$attribute2 = $data[1]; ?>
        <?php else: ?>
            <?php if (isset($model->elements_fields[$modelFieldForm->id][0])): ?>
                <?php $model->$attribute = $model->elements_fields[$modelFieldForm->id][0]; ?>
            <?php endif; ?>
            <?php if (isset($model->elements_fields[$modelFieldForm->id][1])): ?>
                <?php $model->$attribute2 = $model->elements_fields[$modelFieldForm->id][1]; ?>
            <?php endif; ?>
        <?php endif; ?>
        <?= $form->field($model, $attribute, [
            'options' => [
                'id' => 'group-' . $modelFieldForm->id . '-' . $i
            ]
        ])->widget(\phpnt\datepicker\BootstrapDatepicker::class, [
            'type' => \phpnt\datepicker\BootstrapDatepicker::TYPE_RANGE,
            'attribute_2' => $attribute2,
            'autoclose' => true,
            'format' => 'dd.mm.yyyy',
            'language'  => Yii::$app->language,
            'options' => [
                'class' => 'form-control',
                'name' => $modelName . "[elements_fields][$modelFieldForm->id][]",
            ]
        ])->label(Yii::t('app', $modelFieldForm->name)) ?>
        <?php if (isset($model->errors_fields[$modelFieldForm->id][0])): ?>
            <?php $error = $model->errors_fields[$modelFieldForm->id][0]; ?>
            <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
        <?php endif; ?>
        <?php if (isset($model->errors_fields[$modelFieldForm->id][1])): ?>
            <?php $error = $model->errors_fields[$modelFieldForm->id][1]; ?>
            <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="<?= $containerClass ?>">
        <?= $form->field($model, $attribute, [
            'options' => [
                'id' => 'group-' . $modelFieldForm->id . '-' . $i
            ]
        ])->widget(\phpnt\datepicker\BootstrapDatepicker::class, [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy',
            'language'  => Yii::$app->language,
            'options' => [
                'id' => 'field-' . $modelFieldForm->id . '-' . $i,
                'class' => 'form-control',
                'name' => $modelName . "[elements_fields][$modelFieldForm->id][$i]",
                //'value' => isset($model->elements_fields[$modelFieldForm->id][$i]) ? $model->elements_fields[$modelFieldForm->id][$i] : null,
                'value' => $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $model->id),
            ]
        ])->label(Yii::t('app', $modelFieldForm->name)) ?>
        <?php if (isset($model->errors_fields[$modelFieldForm->id][$i])): ?>
            <?php $error = $model->errors_fields[$modelFieldForm->id][$i]; ?>
            <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
        <?php endif; ?>
    </div>
<?php endif; ?>