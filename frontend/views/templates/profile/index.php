<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 09.01.2019
 * Time: 8:15
 */

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelUserForm \common\models\extend\UserExtend */
/* @var $modelProfileTemplateForm \common\widgets\TemplateOfElement\forms\ProfileTemplateForm */
/* @var $manyProfiles array */

BootstrapSelectAsset::register($this);

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = $this->title;

$modelUserForm = Yii::$app->user->identity;
?>
<div id="block-profile" class="profile-default-index">
    <?= BootstrapNotify::widget() ?>
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']) ?>
    </div>

    <?php
    /* @var $modelUserForm \common\models\forms\UserForm */
    if (isset($modelUserForm->document)): ?>
        <div class="col-md-12">
            <?php
            /* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
            $fieldsManage = Yii::$app->fieldsManage;
            $templateData = $fieldsManage->getData($modelUserForm->document_id, $modelUserForm->document->template_id);
            p($templateData);
            ?>
        </div>
    <?php endif; ?>

    <div class="col-md-12">
        <?= DetailView::widget([
            'model' => $modelUserForm,
            'attributes' => [
                'id',
                'email:email',
                'created_at:date',
                'updated_at:date',
                [
                    'attribute' => 'document_id',
                    'format' => 'raw',
                    'value' => call_user_func(function ($modelUserForm) {
                        /* @var $modelUserForm \common\models\forms\UserForm */
                        if (isset($modelUserForm->document)) {
                            return $modelUserForm->document->template->name;
                        }
                        return null;
                    }, $modelUserForm),
                    'captionOptions' => [
                        'style' => 'width: 50% !important;'
                    ]
                ],
            ],
        ]) ?>
    </div>

    <?php if ($modelUserForm->document_id != null): ?>
        <div class="col-md-12 text-right m-b-sm">
            <?= Html::button(Yii::t('app', 'Изменить профиль'), [
                'class' => 'btn btn-primary',
                'title' => Yii::t('app', 'Изменить профиль'),
                'onclick' => '
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['update-profile', 'id_document' => $modelProfileTemplateForm->id]) . '",
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })'
            ]); ?>
        </div>
    <?php else: ?>
        <div class="col-md-12 text-right m-b-sm">
            <?php
            $url = Url::to(['create-profile',
                'url' => Url::to(['/profile/default/refresh-profile']),
                'container' => '#block-profile',
            ]);
            ?>
            <?= Html::button(Yii::t('app', 'Создать профиль'), [
                'class' => 'btn btn-success',
                'title' => Yii::t('app', 'Создать профиль'),
                'onclick' => '
                    $.pjax({
                        type: "POST", 
                        url: "'.$url.'",
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    });'
            ]); ?>
        </div>
    <?php endif; ?>

    <div class="col-md-12 text-right">
        <?php $form = ActiveForm::begin([
            'id' => 'profile-form',
            'action' => Url::to(['/profile/default/logout'])
        ]); ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Выйти'), [
                'class' => 'btn btn-primary',
                'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

