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
/* @var $modelGeoTemplateForm \common\widgets\TemplateOfElement\forms\GeoTemplateForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
/* @var $remoteUrl string */
/* @var $attribute string */
/* @var $inputNameId string */
/* @var $changeAttribute string */
/* @var $value string */
/* @var $hiddenValue int */

$containerSetCookie = 'container-' . $changeAttribute;
?>
<?php $i = 0; ?>
<div class="<?= $containerClass ?>">
    <?php if (isset($modelGeoTemplateForm->elements_fields[$modelFieldForm->id][0])): ?>
        <?php $modelGeoTemplateForm->$changeAttribute = $modelGeoTemplateForm->elements_fields[$modelFieldForm->id][0]; ?>
    <?php endif; ?>
    <?= $form->field($modelGeoTemplateForm, $attribute, [
        'options' => [
            'id' => 'group-' . $modelFieldForm->id . '-' . $i
        ]
    ])->widget(\common\widgets\TypeaheadJS\TypeaheadField::class, [
        'changeAttribute' => $changeAttribute,
        'name' => "GeoTemplateForm[elements_fields][$modelFieldForm->id][$i]",
        'hiddenValue' => $hiddenValue,
        'options' => [
            'id' => $inputNameId,
            'name' => "GeoTemplateForm[elements_fields][$modelFieldForm->id][$i]",
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
                if ("' . $changeAttribute . '" == "id_geo_country") {
                    // если выбрана страна очищаем поля региона и города
                    $("#name_geo_region").val("");
                    $("#id_geo_region").val("");
                    $("#name_geo_city").val("");
                    $("#id_geo_city").val("");
                }
                if ("' . $changeAttribute . '" == "id_geo_region") {
                    // если выбран регион очищаем поля города
                    $("#name_geo_city").val("");
                    $("#id_geo_city").val("");
                }
                $("#' . $changeAttribute . '").val(datum.id);
                $.pjax({
                    type: "GET", 
                    url: "/geo-manage/set-cookie?name=' . $changeAttribute . '&value=" + datum.id,
                    container: "#' . $containerSetCookie . '",
                    push: false,
                    timeout: 20000,
                    scrollTo: false
                });
            }'),
        ],
    ])->label(Yii::t('app', $modelFieldForm->name)) ?>
    <div id="<?= $containerSetCookie ?>"></div>
    <?php if (isset($modelGeoTemplateForm->errors_fields[$modelFieldForm->id][$i])): ?>
        <?php $error = $modelGeoTemplateForm->errors_fields[$modelFieldForm->id][$i]; ?>
        <?php $this->registerJs('addError("#group-' . $modelFieldForm->id . '-' . $i . '", "'.$error.'");') ?>
    <?php endif; ?>
</div>
