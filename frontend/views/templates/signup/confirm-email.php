<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.01.2019
 * Time: 13:05
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Html;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $page array */
/* @var $modelSignupForm \common\models\forms\SignupForm */
/* @var $key integer */
?>
<div class="container m-t-lg">
    <?= BootstrapNotify::widget() ?>
    <?php BootstrapSelectAsset::register($this) ?>
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => Url::to(['/signup/default/confirm-email', 'user_id' => Yii::$app->request->get('user_id')]),
        'options' => ['data-pjax' => true]
    ]); ?>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($modelSignupForm, 'email', ['template' => '{label} 
                                            <div class="input-group">
                                                {input}<span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                             </div>
                                            <i>{hint}</i>{error}'])
                ->textInput(['placeholder' => $modelSignupForm->getAttributeLabel('email')]) ?>
        </div>
        <div class="col-sm-12">
            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('app', 'Подтвердить емайл'), ['class' => 'btn btn-primary text-uppercase full-width']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>