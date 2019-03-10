<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 13:57
 */

use yii\bootstrap\Html;
use yii\helpers\Url;
use common\widgets\TemplateOfElement\SetBasketFields;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $widget \common\widgets\Basket\BasketButton */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $valuePrice array */
?>
<div id="basket-widget" class="m-t-sm">
    <?php $form = ActiveForm::begin([
        'id' => 'form-basket-' . $valuePrice['document_id'],
        'action' => Url::to(['/bm/add-item', 'document_id' => $valuePrice['document_id']]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <?php if (isset($modelDocumentForm->template)): ?>
        <?= SetBasketFields::widget([
            'form' => $form,
            'model' => $modelDocumentForm,
            'valuePrice' => $valuePrice
        ]); ?>
    <?php endif; ?>

    <div class="col-md-12">
        <?= $form->field($modelDocumentForm, 'parent_id')
            ->hiddenInput(['value' => $modelDocumentForm->parent_id])->label(false) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'В корзину'), ['class' => 'btn btn-sm btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $document_id = $valuePrice['document_id'];
    $js = <<< JS
        $("#form-basket-$document_id").on('beforeSubmit', function () { 
            var form = $(this);
                $.pjax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: new FormData($("#form-basket-$document_id")[0]),
                    container: "#basket-product-count",
                    push: false,
                    scrollTo: false,
                    cache: false,
                    contentType: false,
                    timeout: 10000,
                    processData: false
                })
                .done(function(data) {
                    console.log('success');
                    $.pjax({
                        type: "GET", 
                        url: "/bm/update-count",
                        container: "#basket-product-count",
                        push: false,
                        timeout: 20000,
                        scrollTo: false
                    });
                })
                .fail(function () {
                    // request failed
                    console.log('request failed');
                });
            return false; // prevent default form submission
        });
JS;
    $this->registerJs($js);
    ?>
</div>
