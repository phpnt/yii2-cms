<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelGeoTemplateForm \common\widgets\TemplateOfElement\models\forms\GeoTemplateForm */

use yii\bootstrap\Modal;
?>
<?php
Modal::begin([
    'id' => 'select-geo',
    'size' => 'modal-md',
    'header' => '<div class="m-b-sm">'.Yii::t('app', 'Выберите город').':</div>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>

<?= $this->render('_geo-form', ['page' => $page, 'modelGeoTemplateForm' => $modelGeoTemplateForm]) ?>

<div class="clearfix"></div>
<?php
Modal::end();
?>

