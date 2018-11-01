<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.09.2018
 * Time: 10:18
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
<div class="col-md-12">
    <h4><?= Yii::t('app', $modelFieldForm->name) ?></h4>
</div>
<?php $data = $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id) ?>
<?php foreach ($modelFieldForm->list as $item): ?>
    <div class="<?= $containerClass ?>">
        <?php if (isset($data[$i])): ?>
            <?php $modelDocumentForm->$attribute = $data[$i]; ?>
        <?php else: ?>
            <?php if (isset($modelDocumentForm->elements_fields[$modelFieldForm->id][$i])): ?>
                <?php $modelDocumentForm->$attribute = $modelDocumentForm->elements_fields[$modelFieldForm->id][$i]; ?>
            <?php endif; ?>
        <?php endif; ?>
        <?= $form->field($modelDocumentForm, $attribute, [
            'options' => [
                'id' => 'group-' . $modelFieldForm->id . '-' . $i
            ]
        ])->checkbox([
            'id' => 'field-' . $modelFieldForm->id . '-' . $i,
            'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i]",
        ])->label(Yii::t('app', $item)) ?>
        <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][$i])): ?>
            <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][$i]; ?>
            <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
        <?php endif; ?>
    </div>
    <?php $i++; ?>
<?php endforeach; ?>
