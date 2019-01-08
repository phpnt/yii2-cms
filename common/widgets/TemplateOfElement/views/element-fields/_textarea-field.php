<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.09.2018
 * Time: 10:09
 */

/* @var $containerClass string */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
/* @var $attribute string */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<?php $i = 0; ?>
<div class="<?= $containerClass ?>">
    <?= $form->field($modelDocumentForm, $attribute, [
        'options' => [
            'id' => 'group-' . $modelFieldForm->id . '-' . $i
        ]
    ])->textarea([
        'id' => 'field-' . $modelFieldForm->id . '-' . $i,
        'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i]",
        'value' => isset($modelDocumentForm->elements_fields[$modelFieldForm->id][$i]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][$i] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id),
        //'value' => $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id),
    ])->label(Yii::t('app', $modelFieldForm->name)) ?>
    <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][0])): ?>
        <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][0]; ?>
        <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
    <?php endif; ?>
</div>
