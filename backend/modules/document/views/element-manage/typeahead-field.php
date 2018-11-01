<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.09.2018
 * Time: 12:37
 */

/* @var $containerClass string */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
/* @var $remoteUrl string */
/* @var $attribute string */
/* @var $changeAttribute string */
/* @var $value string */
/* @var $hiddenValue int */
?>
<?php $i = 0; ?>
<div class="<?= $containerClass ?>">
    <?php if (isset($modelDocumentForm->elements_fields[$modelFieldForm->id][0])): ?>
        <?php $modelDocumentForm->$changeAttribute = $modelDocumentForm->elements_fields[$modelFieldForm->id][0]; ?>
    <?php endif; ?>
    <?= $form->field($modelDocumentForm, $attribute, [
        'options' => [
            'id' => 'group-' . $modelFieldForm->id . '-' . $i
        ]
    ])->widget(\common\widgets\TypeaheadJS\TypeaheadField::class, [
        'changeAttribute' => $changeAttribute,
        'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i]",
        'hiddenValue' => $hiddenValue,
        'options' => [
            'id' => 'field-' . $modelFieldForm->id . '-' . $i,
            'name' => "DocumentForm[elements_fields][$modelFieldForm->id][$i]",
            'class' => 'typeahead form-control',
            'value' => $value,
        ],
        'bloodhound' => [
            'datumTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
            'queryTokenizer'    => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
            'remote'            => [
                'url'       => $remoteUrl,
                'wildcard'  => '%QUERY'
            ]
        ],
        'typeahead' => [
            'name' => 'name',
            'display' => 'name',
        ],
        'typeaheadEvents' => [
            'typeahead:selected' => new \yii\web\JsExpression(
        'function(obj, datum, name) {
                $("#' . $changeAttribute . '").val(datum.id);
            }'),
        ],
    ])->label(Yii::t('app', $modelFieldForm->name)) ?>
    <?php if (isset($modelDocumentForm->errors_fields[$modelFieldForm->id][$i])): ?>
        <?php $error = $modelDocumentForm->errors_fields[$modelFieldForm->id][$i]; ?>
        <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
    <?php endif; ?>
</div>
