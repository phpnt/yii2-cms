<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 02.06.2016
 * Time: 22:10
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Html;
use phpnt\bootstrapNotify\BootstrapNotify;
use common\widgets\TemplateOfElement\SetGeoFields;

/* @var $this yii\web\View */
/* @var $page array */
/* @var $modelGeoTemplateForm \common\widgets\TemplateOfElement\forms\GeoTemplateForm */
?>
<div id="elements-form-block">
    <?= BootstrapNotify::widget() ?>
    <?php $form = ActiveForm::begin([
        'id' => 'form-geo',
        'action' => Url::to(['/geo/default/index']),
        'options' => ['data-pjax' => true]
    ]); ?>
    <div class="row">

        <?php if ($modelGeoTemplateForm): ?>
            <?= SetGeoFields::widget([
                'form' => $form,
                'modelGeoTemplateForm' => $modelGeoTemplateForm,
            ]); ?>
        <?php endif; ?>

        <div class="col-sm-12">
            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('app', 'Выбрать'), [
                    'id' => 'submit-geo',
                    'class' => 'btn btn-primary text-uppercase full-width',
                ]) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $js = <<< JS
        $('#form-geo').on('beforeSubmit', function () { 
            var form = $(this);
                $.pjax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: new FormData($('#form-geo')[0]),
                    container: "#elements-form-block",
                    push: false,
                    scrollTo: false,
                    cache: false,
                    contentType: false,
                    timeout: 10000,
                    processData: false
                })
                .done(function(data) {
                    
                })
                .fail(function () {
                    // request failed
                    console.log('request failed');
                })
            return false; // prevent default form submission
        });
        function addError(id, message) {
            $( id ).addClass( "has-error" );
            $( id + " .help-block-error" ).text( message ); 
        }
JS;
    $this->registerJs($js); ?>
</div>