<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 01.09.2018
 * Time: 14:49
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $allUserSearch common\models\search\UserSearch */
/* @var $dataProviderUserSearch yii\data\ActiveDataProvider */
?>
<?php Pjax::begin([
    'id' => 'pjax-grid-user-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<?= BootstrapNotify::widget() ?>
<?php if (Yii::$app->user->can('user/manage/create-user')): ?>
    <p>
        <?= Html::button(Yii::t('app', 'Создать пользователя'),
            [
                'class' => 'btn btn-success',
                'onclick' => '
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['/user/manage/create-user']) . '",
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })'
            ]) ?>
    </p>
<?php endif; ?>
<?= GridView::widget([
    'dataProvider' => $dataProviderUserSearch,
    'filterModel' => $allUserSearch,
    'id' => 'grid-user-block',
    'columns' => [
        ['template' => '{view} {update} {delete}',
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => [
                'class' => 'text-center vcenter',
                'style'=>'max-width: 20px !important; width: 20px !important;'
            ],
            'buttons' => [
                'view' => function ($url, $modelUserForm, $id) {
                    /* @var $modelUserForm \common\models\forms\UserForm */
                    if (Yii::$app->user->can('user/manage/view-user')) {
                        return Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0);', [
                            'class' => 'text-info',
                            'title' => Yii::t('app', 'Просмотр пользователя'),
                            'onclick' => '
                            $.pjax({
                                type: "GET",
                                url: "' . Url::to(['/user/manage/view-user', 'id' => $modelUserForm->id]) . '",
                                container: "#pjaxModalUniversal",
                                push: false,
                                timeout: 10000,
                                scrollTo: false
                            })'
                        ]);
                    }
                    return false;
                },
                'update' => function ($url, $modelUserForm, $id) {
                    /* @var $modelUserForm \common\models\forms\UserForm */
                    if (Yii::$app->user->can('user/manage/update-user')) {
                        return Html::a('<i class="fa fa-pen"></i>', 'javascript:void(0);', [
                            'class' => 'text-warning',
                            'title' => Yii::t('app', 'Изменить пользователя'),
                            'onclick' => '
                            $.pjax({
                                type: "GET",
                                url: "' . Url::to(['/user/manage/update-user', 'id' => $modelUserForm->id]) . '",
                                container: "#pjaxModalUniversal",
                                push: false,
                                timeout: 10000,
                                scrollTo: false
                            })'
                        ]);
                    }
                    return false;
                },
                'delete' => function ($url, $modelUserForm, $id) {
                    /* @var $modelUserForm \common\models\forms\UserForm */
                    if (Yii::$app->user->can('user/manage/delete-user')) {
                        return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                            'class' => 'text-danger',
                            'title' => Yii::t('app', 'Удалить пользователя'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/user/manage/confirm-delete-user', 'id' => $modelUserForm->id]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                        ]);
                    }
                    return false;
                },
            ],
        ],
        [
            'attribute' => 'first_name',
            'format' => 'raw',
            'contentOptions' => [
                //'class' => 'vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
            'headerOptions'   => ['class' => 'text-center'],
            'value' => function ($modelUserForm) {
                /* @var $modelUserForm \common\models\forms\UserForm */
                return $modelUserForm->first_name;
            },
        ],
        [
            'attribute' => 'last_name',
            'format' => 'raw',
            'contentOptions' => [
                //'class' => 'vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
            'headerOptions'   => ['class' => 'text-center'],
            'value' => function ($modelUserForm) {
                /* @var $modelUserForm \common\models\forms\UserForm */
                return $modelUserForm->last_name;
            },
        ],
        [
            'attribute' => 'role',
            'format' => 'raw',
            'headerOptions'   => ['class' => 'text-center'],
            'value' => function ($modelUserForm) {
                /* @var $modelUserForm \common\models\forms\UserForm */
                return $modelUserForm->userRole;
            },
            'filter' => Html::activeDropDownList($allUserSearch, 'role', $allUserSearch->userRoles, [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]]),
            'contentOptions' => ['style'=>'max-width: 120px !important; width: 120px !important;'],
        ],
        //'auth_key',
        //'password_hash',
        //'password_reset_token',
        //'email_confirm_token:email',
        [
            'attribute' => 'email',
            'format' => 'raw',
            'contentOptions' => [
                //'class' => 'vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
            'headerOptions'   => ['class' => 'text-center'],
            'value' => function ($modelUserForm) {
                /* @var $modelUserForm \common\models\forms\UserForm */
                if (Yii::$app->user->can('admin')) {
                    return $modelUserForm->email;
                }
                return Yii::t('app', 'У Вас нет прав для просмотра.');
            },
        ],
        //'image',
        //'sex',
        //'birthday',
        //'phone',
        //'id_geo_country',
        //'id_geo_city',
        //'address',
        [
            'attribute' => 'status',
            'format' => 'raw',
            'value' => function ($modelUserForm) {
                /* @var $modelUserForm \common\models\extend\UserExtend */
                return $modelUserForm->statusUser;
            },
            'filter' => Html::activeDropDownList($allUserSearch, 'status', $allUserSearch->statusList, [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]]),
            'contentOptions' => ['style'=>'max-width: 120px !important; width: 120px !important;'],
        ],
        //'ip',
        //'created_at',
        //'updated_at',
        //'login_at',
    ],
]); ?>
<?php
$js = <<< JS
    $('.selectpicker').selectpicker({});
JS;
$this->registerJs($js); ?>
<?php Pjax::end(); ?>
