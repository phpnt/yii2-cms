<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 04.03.2019
 * Time: 21:59
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
        'action' => Url::to(['/bm/update-item', 'alias_menu_item' => 'basket', 'document_id' => $modelDocumentForm->id]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <?php if (isset($modelDocumentForm->template)): ?>
        <?= SetBasketFields::widget([
            'form' => $form,
            'model' => $modelDocumentForm,
            'valuePrice' => $valuePrice
        ]); ?>
    <?php endif; ?>

    <?= $form->field($modelDocumentForm, 'parent_id')
        ->hiddenInput(['value' => $modelDocumentForm->parent_id])->label(false) ?>

    <div class="form-group">
        <?php if ($valuePrice['item_max'] > 1): ?>
            <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-sm btn-primary m-t-sm']) ?>
        <?php endif; ?>
        <?= Html::button(Yii::t('app', 'Удалить'), [
            'class' => 'btn btn-sm btn-danger m-t-sm',
            'onclick' => '
                if (confirm("' . Yii::t('app', 'Удалить') . '?")) {
                    $.pjax({
                        type: "GET", 
                        url: "/bm/delete-item?alias_menu_item=basket&document_id=' . $modelDocumentForm->id . '",
                        container: "#main-body-container",
                        push: false,
                        timeout: 20000,
                        scrollTo: false
                    })
                    .done(function(data) {
                        console.log("success");
                        $.pjax({
                            type: "GET", 
                            url: "/bm/update-count",
                            container: "#basket-product-count",
                            push: false,
                            timeout: 20000,
                            scrollTo: false
                        }); 
                    });              
                }
            ',
        ]) ?>
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
                    container: "#main-body-container",
                    push: false,
                    scrollTo: false,
                    cache: false,
                    contentType: false,
                    timeout: 20000,
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
                })
            return false; // prevent default form submission
        });
JS;
    $this->registerJs($js);
    ?>
</div>
