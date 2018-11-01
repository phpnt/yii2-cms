<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 01.09.2018
 * Time: 16:04
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $allAuthRuleSearch common\models\search\AuthRuleSearch */
/* @var $dataProviderAuthRuleSearch yii\data\ActiveDataProvider */
?>
<?php Pjax::begin([
    'id' => 'pjax-grid-auth-rule-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<?= BootstrapNotify::widget() ?>
<?php if (Yii::$app->user->can('role/manage/create-auth-rule')): ?>
    <p>
        <?= Html::button(Yii::t('app', 'Создать правило'),
            [
                'class' => 'btn btn-success',
                'onclick' => '
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['/role/manage/create-auth-rule']) . '",
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })'
            ]) ?>
    </p>
<?php endif; ?>
<?= GridView::widget([
    'dataProvider' => $dataProviderAuthRuleSearch,
    'filterModel' => $allAuthRuleSearch,
    'id' => 'grid-auth-rule-block',
    'columns' => [
        [
            'template' => '{view} {update} {delete}',
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => [
                'class' => 'text-center vcenter',
                'style'=>'max-width: 20px !important; width: 20px !important;',
            ],
            'buttons' => [
                'view' => function ($url, $modelAuthRuleForm, $id) {
                    /* @var $modelAuthRuleForm \common\models\forms\AuthRuleForm */
                    if (Yii::$app->user->can('role/manage/view-auth-rule')) {
                        return Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0);', [
                            'class' => 'text-info',
                            'title' => Yii::t('app', 'Просмотр правила'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/role/manage/view-auth-rule', 'name' => $modelAuthRuleForm->name]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 20000,
                                    scrollTo: false
                                })'
                        ]);
                    }
                },
                'update' => function ($url, $modelAuthRuleForm, $id) {
                    /* @var $modelAuthRuleForm \common\models\forms\AuthRuleForm */
                    if (Yii::$app->user->can('role/manage/update-auth-rule')) {
                        return Html::a('<i class="fa fa-pen"></i>', 'javascript:void(0);', [
                            'class' => 'text-warning',
                            'title' => Yii::t('app', 'Изменить правило'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/role/manage/update-auth-rule', 'name' => $modelAuthRuleForm->name]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 20000,
                                    scrollTo: false
                                })'
                        ]);
                    }
                },
                'delete' => function ($url, $modelAuthRuleForm, $id) {
                    /* @var $modelAuthRuleForm \common\models\forms\AuthRuleForm */
                    if (Yii::$app->user->can('role/manage/delete-auth-rule')) {
                        return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                            'class' => 'text-danger',
                            'title' => Yii::t('app', 'Удалить правило'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/role/manage/confirm-delete-auth-rule', 'name' => $modelAuthRuleForm->name]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 20000,
                                    scrollTo: false
                                })'
                        ]);
                    }
                },
            ],
        ],
        'name',
        'data:ntext',
        'created_at:date',
        'updated_at:date',
    ],
]); ?>
<?php
$js = <<< JS
    $('.selectpicker').selectpicker({});
JS;
$this->registerJs($js); ?>
<?php Pjax::end(); ?>