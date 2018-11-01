<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 01.09.2018
 * Time: 15:42
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $allAuthItemSearch common\models\search\AuthItemSearch */
/* @var $dataProviderAuthItemSearch yii\data\ActiveDataProvider */
/* @var $modelAuthItemForm \common\models\forms\AuthItemForm */
?>
<?php Pjax::begin([
    'id' => 'pjax-grid-auth-item-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<?php if (Yii::$app->user->can('role/manage/update-auth-item')): ?>
    <div class="box">
        <div class="box-header with-border">
            <?php if (Yii::$app->user->can('role/manage/create-auth-item')): ?>
                <p><?= Html::button(Yii::t('app', 'Создать роль или разрешение'),
                        [
                            'class' => 'btn btn-success',
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/role/manage/create-auth-item']) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                        ]) ?></p>
            <?php endif; ?>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body table-responsive">
            <div class="row">
                <div class="col-md-12">
                    <?= $this->render('_form-auth-item-block', [
                        'modelAuthItemForm' => $modelAuthItemForm
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="box-footer">
        </div>
    </div>
<?php endif; ?>

<div class="box">
    <div class="box-header with-border">
        <?= Yii::t('app', 'Все роли и разрешения') ?>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body table-responsive">
        <div class="row">
            <div class="col-md-12">
                <?= BootstrapNotify::widget() ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProviderAuthItemSearch,
                    'filterModel' => $allAuthItemSearch,
                    'id' => 'grid-auth-item-block',
                    'columns' => [
                        [
                            'template' => '{view} {update} {delete}',
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => [
                                'class' => 'text-center vcenter',
                                'style'=>'max-width: 20px !important; width: 20px !important;',
                            ],
                            'buttons' => [
                                'view' => function ($url, $modelAuthItemForm, $id) {
                                    /* @var $modelAuthItemForm \common\models\forms\AuthItemForm */
                                    if (Yii::$app->user->can('role/manage/view-auth-item')) {
                                        return Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0);', [
                                            'class' => 'text-info',
                                            'title' => Yii::t('app', 'Просмотр роли или разрешения'),
                                            'onclick' => '
                                                $.pjax({
                                                    type: "GET",
                                                    url: "' . Url::to(['/role/manage/view-auth-item', 'name' => $modelAuthItemForm->name]) . '",
                                                    container: "#pjaxModalUniversal",
                                                    push: false,
                                                    timeout: 10000,
                                                    scrollTo: false
                                                })'
                                        ]);
                                    }
                                },
                                'update' => function ($url, $modelAuthItemForm, $id) {
                                    /* @var $modelAuthItemForm \common\models\forms\AuthItemForm */
                                    if (Yii::$app->user->can('role/manage/update-auth-item')) {
                                        return Html::a('<i class="fa fa-pen"></i>', 'javascript:void(0);', [
                                            'class' => 'text-warning',
                                            'title' => Yii::t('app', 'Изменить роль или разрешение'),
                                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/role/manage/update-auth-item', 'name' => $modelAuthItemForm->name]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                                        ]);
                                    }
                                },
                                'delete' => function ($url, $modelAuthItemForm, $id) {
                                    /* @var $modelAuthItemForm \common\models\forms\AuthItemForm */
                                    if (Yii::$app->user->can('role/manage/delete-auth-item')) {
                                        return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                                            'class' => 'text-danger',
                                            'title' => Yii::t('app', 'Удалить роль или разрешение'),
                                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/role/manage/confirm-delete-auth-item', 'name' => $modelAuthItemForm->name]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                                        ]);
                                    }
                                },
                            ],
                        ],
                        'name',
                        [
                            'attribute' => 'type',
                            'format' => 'raw',
                            'value' => function ($modelAuthItemForm) {
                                /* @var $modelAuthItemForm \common\models\forms\AuthItemForm */
                                return $modelAuthItemForm->getTypeIs();
                            },
                            'filter' => Html::activeDropDownList($allAuthItemSearch, 'type', $allAuthItemSearch->getTypeList(), [
                                'class'  => 'form-control selectpicker',
                                'data' => [
                                    'style' => 'btn-default',
                                    'live-search' => 'false',
                                    'title' => '---'
                                ]]),
                            'contentOptions' => ['style'=>'max-width: 120px !important; width: 120px !important;'],
                        ],
                        'description:ntext',
                        //'rule_name',
                        //'data:ntext',
                        //'created_at',
                        //'updated_at',
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
    </div>
</div>
<?php
$js = <<< JS
    $('.selectpicker').selectpicker({});
JS;
$this->registerJs($js); ?>
<?php Pjax::end(); ?>
