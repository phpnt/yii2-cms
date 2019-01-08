<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.09.2018
 * Time: 8:43
 */

use common\models\Constants;

/* @var $containerClass string */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
/* @var $attribute string */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<?php $i = 0; ?>
<?php if ($modelFieldForm->type == Constants::FIELD_TYPE_INT_RANGE || $modelFieldForm->type == Constants::FIELD_TYPE_FLOAT_RANGE): ?>
    <?php if (!$data = $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id)): ?>
        <?php $data = []; $data[0] = null; $data[1] = null; ?>
    <?php endif; ?>
    <div class="col-md-12">
        <h4><?= Yii::t('app', $modelFieldForm->name) ?></h4>
    </div>
    <?php for ($y = 0; $y <= 1; $y++): ?>
        <div class="<?= $containerClass ?>">
            <?= $form->field($modelDocumentForm, $attribute, [
                'options' => [
                    'id' => 'group-' . $modelFieldForm->id . '-' . $i
                ]
            ])->textInput([
                'id' => 'field-' . $modelFieldForm->id . '-' . $i,
                'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i]",
                'value' => isset($modelDocumentForm->elements_fields[$modelFieldForm->id][$i]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][$i] : $data[$i],
                //'value' => $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id),
            ])->label($y == 0 ? Yii::t('app', 'От') : Yii::t('app', 'До')) ?>
            <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][$i])): ?>
                <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][$i]; ?>
                <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
            <?php endif; ?>
        </div>
        <?php $i++; ?>
    <?php endfor; ?>
<?php else: ?>
    <div class="<?= $containerClass ?>">
        <?= $form->field($modelDocumentForm, $attribute, [
            'options' => [
                'id' => 'group-' . $modelFieldForm->id . '-' . $i
            ]
        ])->textInput([
            'id' => 'field-' . $modelFieldForm->id . '-' . $i,
            'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i]",
            'value' => isset($modelDocumentForm->elements_fields[$modelFieldForm->id][$i]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][$i] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id),
            //'value' => $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id),
        ])->label(Yii::t('app', $modelFieldForm->name)) ?>
        <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][$i])): ?>
            <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][$i]; ?>
            <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
