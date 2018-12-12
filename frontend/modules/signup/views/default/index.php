<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelSignupForm \common\models\forms\SignupForm */

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signup-default-index">
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']); ?>
        <?php $form = ActiveForm::begin([
            'id' => 'form-signup',
        ]); ?>

        <?= $form->field($modelSignupForm, 'first_name')->textInput([
            'maxlength' => true,
            'placeholder' => $modelSignupForm->getAttributeLabel('first_name')
        ]);?>

        <?= $form->field($modelSignupForm, 'last_name')->textInput([
            'maxlength' => true,
            'placeholder' => $modelSignupForm->getAttributeLabel('last_name')
        ]);?>

        <?= $form->field($modelSignupForm, 'email')->textInput([
            'maxlength' => true,
            'placeholder' => $modelSignupForm->getAttributeLabel('email')
        ]);?>

        <?= $form->field($modelSignupForm, 'password')->passwordInput([
            'maxlength' => true,
            'placeholder' => $modelSignupForm->getAttributeLabel('password')
        ]); ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Регистрация'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
