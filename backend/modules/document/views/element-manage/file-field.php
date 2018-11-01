<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.09.2018
 * Time: 14:26
 */

use common\models\Constants;

/* @var $this yii\web\View */
/* @var $containerClass string */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
/* @var $attribute string */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

switch ($modelFieldForm->type) {
    case Constants::FIELD_TYPE_FILE:
        $attribute = 'file';
        break;
    case Constants::FIELD_TYPE_FEW_FILES:
        $attribute = 'few_files[]';
        break;
}
?>
<?php $i = 0; ?>
<?php if ($modelFieldForm->type == Constants::FIELD_TYPE_FEW_FILES): ?>
    <div class="<?= $containerClass ?>">
        <?= $form->field($modelDocumentForm, $attribute, [
            'options' => [
                'id' => 'group-' . $modelFieldForm->id . '-' . $i
            ]
        ])->fileInput([
            'id' => 'field-' . $modelFieldForm->id . '-' . $i,
            //'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i][]",
            'multiple' => true
        ])->label(Yii::t('app', $modelFieldForm->name)) ?>
        <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][$i])): ?>
            <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][$i]; ?>
            <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
        <?php endif; ?>

        <?php $manyValueFileForm = $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id); ?>
        <?php if ($manyValueFileForm): ?>
            <?= $this->render('_files', [
                'manyValueFileForm' => $manyValueFileForm
            ]) ?>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="<?= $containerClass ?>">
        <?= $form->field($modelDocumentForm, $attribute, [
            'options' => [
                'id' => 'group-' . $modelFieldForm->id . '-' . $i
            ]
        ])->fileInput([
            'id' => 'field-' . $modelFieldForm->id . '-' . $i,
            //'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i]",
        ])->label(Yii::t('app', $modelFieldForm->name)) ?>
        <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][$i])): ?>
            <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][$i]; ?>
            <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
        <?php endif; ?>

        <?php $modelValueFileForm = $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id); ?>
        <?php if ($modelValueFileForm): ?>
            <?= $this->render('_file', [
                'modelValueFileForm' => $modelValueFileForm
            ]) ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
