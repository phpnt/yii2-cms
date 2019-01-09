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
/* @var $model \common\models\forms\DocumentForm */
/* @var $modelName string */
/* @var $modelFieldForm \common\models\forms\FieldForm */
/* @var $attribute string */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<?php $i = 0; ?>
<div class="<?= $containerClass ?>">
    <?= $form->field($model, $attribute, [
        'options' => [
            'id' => 'group-' . $modelFieldForm->id . '-' . $i
        ]
    ])->textarea([
        'id' => 'field-' . $modelFieldForm->id . '-' . $i,
        'name' => $modelName . "[elements_fields][$modelFieldForm->id][$i]",
        'value' => isset($model->elements_fields[$modelFieldForm->id][$i]) ? $model->elements_fields[$modelFieldForm->id][$i] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $model->id),
        //'value' => $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $model->id),
    ])->label(Yii::t('app', $modelFieldForm->name)) ?>
    <?php if (isset($model->errors_fields[$modelFieldForm->id][0])): ?>
        <?php $error = $model->errors_fields[$modelFieldForm->id][0]; ?>
        <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
    <?php endif; ?>
</div>
