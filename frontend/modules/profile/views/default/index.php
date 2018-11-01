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
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use common\widgets\Like\Like;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelUserForm \common\models\forms\UserForm */

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = $this->title;

$modelUserForm = Yii::$app->user->identity;
?>
<div class="profile-default-index">
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']) ?>
    </div>
    <div class="col-md-12">
        <?= DetailView::widget([
            'model' => $modelUserForm,
            'attributes' => [
                'id',
                'first_name',
                'last_name',
                'email:email',
                'created_at:date',
                'updated_at:date',
            ],
        ]) ?>
    </div>
    <div class="col-md-12 text-right">
        <?php $form = ActiveForm::begin([
            'id' => 'profile-form',
            'action' => Url::to(['/'.$page['alias'].'/default/logout'])
        ]); ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Выйти'), [
                'class' => 'btn btn-primary',
                'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-12 text-right">
        <?= Like::widget(['document_id' => $page['id']]) ?>
    </div>
</div>
