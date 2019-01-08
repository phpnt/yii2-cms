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
/* @var $widget \common\widgets\TemplateOfElement\SetGeoFields */
/* @var $modelGeoTemplateForm \common\widgets\TemplateOfElement\forms\GeoTemplateForm */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

$form = $widget->form;
$modelGeoTemplateForm = $widget->modelGeoTemplateForm;
?>
<?php foreach ($modelGeoTemplateForm->fields as $modelFieldForm): ?>

    <?php /* @var $modelFieldForm \common\models\forms\FieldForm */ ?>
    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_COUNTRY): ?>
        <?php $placeholder = $fieldsManage->getCountryName(); ?>
        <?php $hiddenValue = $fieldsManage->getCountryId(); ?>
        <?= $this->render('_typeahead-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelGeoTemplateForm' => $modelGeoTemplateForm,
            'modelFieldForm' => $modelFieldForm,
            'remoteUrl' => '/geo-manage/get-country?query=%QUERY&lang='.Yii::$app->language,
            'attribute' => 'value_string',
            'inputNameId' => 'name_geo_country',
            'changeAttribute' => 'id_geo_country',
            'value' => $placeholder,
            'hiddenValue' => $hiddenValue
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_REGION): ?>
        <?php $placeholder = $fieldsManage->getRegionName(); ?>
        <?php $hiddenValue = $fieldsManage->getRegionId(); ?>
        <?= $this->render('_typeahead-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelGeoTemplateForm' => $modelGeoTemplateForm,
            'modelFieldForm' => $modelFieldForm,
            'remoteUrl' => '/geo-manage/get-region?query=%QUERY&lang='.Yii::$app->language,
            'attribute' => 'value_string',
            'inputNameId' => 'name_geo_region',
            'changeAttribute' => 'id_geo_region',
            'value' => $placeholder,
            'hiddenValue' => $hiddenValue
        ]); ?>
    <?php elseif ($modelFieldForm->type == Constants::FIELD_TYPE_CITY): ?>
        <?php $placeholder = $fieldsManage->getCityName(); ?>
        <?php $hiddenValue = $fieldsManage->getCityId(); ?>
        <?= $this->render('_typeahead-field', [
            'containerClass' => 'col-md-12',
            'form' => $form,
            'modelGeoTemplateForm' => $modelGeoTemplateForm,
            'modelFieldForm' => $modelFieldForm,
            'remoteUrl' => '/geo-manage/get-city?query=%QUERY&lang='.Yii::$app->language,
            'attribute' => 'value_string',
            'inputNameId' => 'name_geo_city',
            'changeAttribute' => 'id_geo_city',
            'value' => $placeholder,
            'hiddenValue' => $hiddenValue
        ]); ?>
    <?php endif; ?>
<?php endforeach; ?>
