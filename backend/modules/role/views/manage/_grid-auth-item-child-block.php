<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 01.09.2018
 * Time: 15:29
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $allAuthItemChildSearch common\models\search\AuthItemChildSearch */
/* @var $dataProviderAuthItemChildSearch yii\data\ActiveDataProvider */
?>
<?php Pjax::begin([
    'id' => 'pjax-grid-auth-item-child-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<?= BootstrapNotify::widget() ?>
<?php if (Yii::$app->user->can('role/manage/create-auth-item-child')): ?>
    <p>
        <?= Html::button(Yii::t('app', 'Создать наследование RBAC'),
            [
                'class' => 'btn btn-success',
                'onclick' => '
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['/role/manage/create-auth-item-child']) . '",
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })'
            ]) ?>
    </p>
<?php endif; ?>
<?= GridView::widget([
    'dataProvider' => $dataProviderAuthItemChildSearch,
    'filterModel' => $allAuthItemChildSearch,
    'id' => 'grid-auth-item-child-child-block',
    'columns' => [
        [
            'template' => '{delete}',
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => [
                'class' => 'text-center vcenter',
                'style'=>'max-width: 20px !important; width: 20px !important;',
            ],
            'buttons' => [
                'delete' => function ($url, $modelAuthItemChildForm, $id) {
                    /* @var $modelAuthItemChildForm \common\models\forms\AuthItemChildForm */
                    if (Yii::$app->user->can('role/manage/delete-auth-item-child')) {
                        return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                            'class' => 'text-danger',
                            'title' => Yii::t('app', 'Удалить наследование RBAC'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/role/manage/confirm-delete-auth-item-child', 'parent' => $modelAuthItemChildForm->parent]) . '",
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
        'parent',
        'child',
    ],
]); ?>
<?php Pjax::end(); ?>