<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 25.08.2018
 * Time: 13:41
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $modelDocumentSearchFolder common\models\search\DocumentSearch */
/* @var $dataProviderDocumentSearchFolders yii\data\ActiveDataProvider */
?>
<?php Pjax::begin([
    'id' => 'pjax-grid-folders-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<?= BootstrapNotify::widget([]) ?>
<?php if (Yii::$app->user->can('create-folder')): ?>
    <p>
        <?= Html::button(Yii::t('app', 'Создать папку'),
            [
                'class' => 'btn btn-success',
                'onclick' => '
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['folder-manage/create-folder']) . '",
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })'
            ]) ?>
    </p>
<?php endif; ?>
<?= GridView::widget([
    'dataProvider' => $dataProviderDocumentSearchFolders,
    'filterModel' => $modelDocumentSearchFolder,
    'filterUrl' => ['refresh-folders'],
    'options' => [
        'id' => 'grid-folders-block',
        'class' => 'grid-view'
    ],
    'columns' => [
            [
            'template' => '{view} {update} {delete}',
            'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'text-center vcenter',
                    'style'=>'max-width: 20px !important; width: 20px !important;',
                ],
            'buttons' => [
                'view' => function ($url, $modelDocumentForm, $id) {
                    /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                    if (Yii::$app->user->can('view-folder')) {
                        return Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0);', [
                            'class' => 'text-info',
                            'title' => Yii::t('app', 'Просмотр папки'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['folder-manage/view-folder', 'id' => $modelDocumentForm->id]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                        ]);
                    }
                },
                'update' => function ($url, $modelDocumentForm, $id) {
                    /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                    if (Yii::$app->user->can('update-folder')) {
                        return Html::a('<i class="fa fa-pen"></i>', 'javascript:void(0);', [
                            'class' => 'text-warning',
                            'title' => Yii::t('app', 'Изменить папку'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['folder-manage/update-folder', 'id' => $modelDocumentForm->id]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                        ]);
                    }
                },
                'delete' => function ($url, $modelDocumentForm, $id) {
                    /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                    if (Yii::$app->user->can('delete-folder')) {
                        return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                            'class' => 'text-danger',
                            'title' => Yii::t('app', 'Удалить папку'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['folder-manage/confirm-delete-folder', 'id' => $modelDocumentForm->id]) . '",
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
        [
            'class' => 'yii\grid\SerialColumn',
            'contentOptions' => [
                'class' => 'text-center vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
        ],
        [
            'label' => 'Папка',
            'format' => 'raw',
            'contentOptions' => [
                'class' => 'text-center vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
            'value' => function ($modelDocumentForm) {
                /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                if (Yii::$app->user->can('manage-element')) {
                    return Html::a($modelDocumentForm->folder, 'javascript:void(0);', [
                        'class' => 'text-info',
                        'title' => Yii::t('app', 'Просмотр папки'),
                        'onclick' => '
                            $.pjax({
                                type: "GET",
                                url: "' . Url::to(['folder-manage/view-elements', 'id_folder' => $modelDocumentForm->id]) . '",
                                container: "#pjax-grid-elements-block", 
                                push: false,
                                timeout: 10000,
                                scrollTo: false
                            })'
                    ]);
                }
                return $modelDocumentForm->folder;
            },
        ],
        [
            'attribute' => 'status',
            'format' => 'raw',
            'contentOptions' => [
                'class' => 'text-center vcenter',
                'style' => 'max-width: 130px !important; width: 130px !important;'
            ],
            'value' => function ($modelDocumentForm) {
                /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                return $modelDocumentForm->statusItem;
            },
            'filter' => Html::activeDropDownList($modelDocumentSearchFolder, 'status', $modelDocumentSearchFolder->statusList, [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]]),
            //'contentOptions' => ['style'=>'max-width: 20px !important; width: 20px !important;'],
        ],
        //'id',
        //'name',
        //'alias',
        //'title',
        //'meta_keywords:ntext',
        //'meta_description:ntext',
        //'annotation:ntext',
        //'content:ntext',
        //'image',
        //'status',
        //'is_folder',
        //'parent_id',
        //'template_id',
        //'created_at',
        //'updated_at',
        //'created_by',
        //'updated_by',
        //'position',
    ],
]); ?>
<?php
$js = <<< JS
    $('.selectpicker').selectpicker({});
JS;
$this->registerJs($js); ?>
<?php Pjax::end(); ?>
