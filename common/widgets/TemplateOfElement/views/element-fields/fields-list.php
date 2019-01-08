<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.01.2019
 * Time: 6:27
 */

use common\models\Constants;

/* @var $this \yii\web\View */
/* @var $widget \common\widgets\TemplateOfElement\SetElementFields */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

$form = $widget->form;
$modelDocumentForm = $widget->modelDocumentForm;
?>
<?php foreach ($modelDocumentForm->template->fields as $modelFieldForm): ?>
    <?php /* @var $modelFieldForm \common\models\forms\FieldForm */ ?>
    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_INT): ?>
        <?= $this->render('_text-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_int',
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_INT_RANGE): ?>
        <?= $this->render('_text-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_int',
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FLOAT ||
        $modelFieldForm->type == Constants::FIELD_TYPE_PRICE): ?>
        <?= $this->render('_text-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_number',
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FLOAT_RANGE): ?>
        <?= $this->render('_text-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_number',
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_STRING ||
        $modelFieldForm->type == Constants::FIELD_TYPE_ADDRESS ||
        $modelFieldForm->type == Constants::FIELD_TYPE_EMAIL ||
        $modelFieldForm->type == Constants::FIELD_TYPE_URL ||
        $modelFieldForm->type == Constants::FIELD_TYPE_SOCIAL ||
        $modelFieldForm->type == Constants::FIELD_TYPE_YOUTUBE): ?>
        <?= $this->render('_text-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_string',
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_TEXT): ?>
        <?= $this->render('_textarea-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_string',
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_CHECKBOX): ?>
        <?= $this->render('_checkboxes-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_int',
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_RADIO): ?>
        <?= $this->render('_radios-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_int',
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_LIST): ?>
        <?= $this->render('_list-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_int',
            'modelFieldForm' => $modelFieldForm,
            'multiple' => false
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_LIST_MULTY): ?>
        <?= $this->render('_list-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_int',
            'modelFieldForm' => $modelFieldForm,
            'multiple' => true
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_DATE): ?>
        <?= $this->render('_date-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'value_string',
            'attribute2' => 'input_date_to',
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_DATE_RANGE): ?>
        <?= $this->render('_date-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'attribute' => 'input_date_from',
            'attribute2' => 'input_date_to',
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_COUNTRY): ?>
        <?php $id_geo_country = isset($modelDocumentForm->elements_fields[$modelFieldForm->id][0]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][0] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id); ?>
        <?php $placeholder = $fieldsManage->getCountryName($id_geo_country); ?>
        <?php $hiddenValue = $id_geo_country ? $id_geo_country : $fieldsManage->getCountryId(); ?>
        <?= $this->render('_typeahead-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'modelFieldForm' => $modelFieldForm,
            'remoteUrl' => '/geo-manage/get-country?query=%QUERY&lang='.Yii::$app->language,
            'attribute' => 'value_string',
            'inputNameId' => 'name_geo_country',
            'changeAttribute' => 'id_geo_country',
            'value' => $placeholder,
            'hiddenValue' => $hiddenValue
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_REGION): ?>
        <?php $id_geo_region = isset($modelDocumentForm->elements_fields[$modelFieldForm->id][0]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][0] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id); ?>
        <?php $placeholder = $fieldsManage->getRegionName($id_geo_region); ?>
        <?php $hiddenValue = $id_geo_region ? $id_geo_region : $fieldsManage->getRegionId(); ?>
        <?= $this->render('_typeahead-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'modelFieldForm' => $modelFieldForm,
            'remoteUrl' => '/geo-manage/get-region?query=%QUERY&lang='.Yii::$app->language,
            'attribute' => 'value_string',
            'inputNameId' => 'name_geo_region',
            'changeAttribute' => 'id_geo_region',
            'value' => $placeholder,
            'hiddenValue' => $hiddenValue
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_CITY): ?>
        <?php $id_geo_city = isset($modelDocumentForm->elements_fields[$modelFieldForm->id][0]) ? $modelDocumentForm->elements_fields[$modelFieldForm->id][0] : $fieldsManage->getValue($modelFieldForm->id, $modelFieldForm->type, $modelDocumentForm->id); ?>
        <?php $placeholder = $fieldsManage->getCityName($id_geo_city); ?>
        <?php $hiddenValue = $id_geo_city ? $id_geo_city : $fieldsManage->getCityId(); ?>
        <?= $this->render('_typeahead-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'modelFieldForm' => $modelFieldForm,
            'remoteUrl' => '/geo-manage/get-city?query=%QUERY&lang='.Yii::$app->language,
            'attribute' => 'value_string',
            'inputNameId' => 'name_geo_city',
            'changeAttribute' => 'id_geo_city',
            'value' => $placeholder,
            'hiddenValue' => $hiddenValue
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FILE ||
        $modelFieldForm->type == Constants::FIELD_TYPE_FEW_FILES
    ): ?>
        <?= $this->render('_file-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelDocumentForm' => $modelDocumentForm,
            'modelFieldForm' => $modelFieldForm,
        ]); ?>
    <?php endif; ?>
<?php endforeach; ?>
