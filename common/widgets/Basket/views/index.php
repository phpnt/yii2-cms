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
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelBasketForm \common\models\forms\BasketForm */
?>
<div id="basket-widget">
    <?php $form = ActiveForm::begin([
        'id' => 'form-basket',
        'options' => ['data-pjax' => true]
    ]); ?>
    <div class="form-group">
        <?= $form->field($modelBasketForm, 'quantity')
            ->hiddenInput(['value' => 1])->label(false) ?>
        <?= Html::a(Yii::t('app', 'В корзину'), 'javascript:void(0);', [
            'class' => 'btn btn-default',
            'onclick' => '
                $.pjax({
                    type: "POST", 
                    url: "'.Url::to(['/bm/update', 'document_id' => $modelBasketForm->document_id]).'",
                    data: $("#form-basket").serializeArray(),
                    container: "#basket-product-count",
                    push: false,
                    timeout: 10000,
                    scrollTo: false
                });'
    ]); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>