<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 31.08.2018
 * Time: 6:16
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $modelCommentSearch \common\models\search\CommentSearch */
/* @var $dataProviderCommentSearch yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Управление комментариями');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin([
    'id' => 'pjax-grid-comment-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<div class="comment-manage-index">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <?= Html::a(Yii::t('app', 'Экспорт комментариев в CSV'),
                    Url::to(['/csv-manager/export',
                        'models[0]' => \common\models\search\CommentSearch::class,
                        'with_header' => true
                    ]),
                    ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Комментарии') ?></h3><br>
                <div class="box-tools pull-right">
                    <span data-toggle="tooltip-comment" title="" class="btn btn-box-tool" data-original-title="
                    <?= Yii::t('app', 'Таблица ‘comment’. Комментарии пользователей.'); ?>
                    "><i class="fas fa-question"></i>
                    </span>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'filterModel' => $modelCommentSearch,
                    'dataProvider' => $dataProviderCommentSearch,
                    'tableOptions'  => [
                        'class' => 'table table-striped table-bordered table-hover dataTables-example dataTable dtr-inline',
                        'aria-describedby' => 'DataTables_Table_0_info',
                        'role'  => 'grid'
                    ],
                    'rowOptions'=>function ($modelSourceMessageForm, $key, $index, $grid){
                        $class=$index%2?'gradeA odd':'gradeA even';
                        return [
                            'key'=>$key,
                            'index'=>$index,
                            'class'=>$class
                        ];
                    },
                    'columns' => [
                        [
                            'template' => '{view} {update} {delete}',
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => [
                                'class' => 'text-center vcenter',
                                'style'=>'max-width: 20px !important; width: 20px !important;'
                            ],
                            'buttons' => [
                                'view' => function ($url, $modelCommentSearch, $id) {
                                    /* @var $modelCommentSearch \common\models\search\CommentSearch */
                                    if (Yii::$app->user->can('comment/manage/view-comment')) {
                                        return Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0);', [
                                            'class' => 'text-info',
                                            'title' => Yii::t('app', 'Просмотр комментария'),
                                            'onclick' => '
                                                $.pjax({
                                                    type: "GET",
                                                    url: "' . Url::to(['/comment/manage/view-comment', 'id' => $modelCommentSearch->id]) . '",
                                                    container: "#pjaxModalUniversal",
                                                    push: false,
                                                    timeout: 10000,
                                                    scrollTo: false
                                                })'
                                        ]);
                                    }
                                    return false;
                                },
                                'update' => function ($url, $modelCommentSearch, $id) {
                                    /* @var $modelCommentSearch \common\models\search\CommentSearch */
                                    if (Yii::$app->user->can('comment/manage/update-comment')) {
                                        return Html::a('<i class="fa fa-pen"></i>', 'javascript:void(0);', [
                                            'class' => 'text-warning',
                                            'title' => Yii::t('app', 'Изменить комментарий'),
                                            'onclick' => '
                                                $.pjax({
                                                    type: "GET",
                                                    url: "' . Url::to(['/comment/manage/update-comment', 'id' => $modelCommentSearch->id]) . '",
                                                    container: "#pjaxModalUniversal",
                                                    push: false,
                                                    timeout: 10000,
                                                    scrollTo: false
                                                })'
                                        ]);
                                    }
                                    return false;
                                },
                                'delete' => function ($url, $modelCommentSearch, $id) {
                                    /* @var $modelCommentSearch \common\models\search\CommentSearch */
                                    if (Yii::$app->user->can('comment/manage/delete-comment')) {
                                        return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                                            'class' => 'text-danger',
                                            'title' => Yii::t('app', 'Удалить комментарий'),
                                            'onclick' => '
                                                $.pjax({
                                                    type: "GET",
                                                    url: "' . Url::to(['/comment/manage/confirm-delete-comment', 'id' => $modelCommentSearch->id]) . '",
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
                            'label' => Yii::t('app', 'Пользователь'),
                            'format' => 'raw',
                            'contentOptions' => [
                                'class' => 'text-center vcenter',
                                //'style' => 'max-width: 100px !important; width: 100px !important;'
                            ],
                            'headerOptions'   => ['class' => 'text-center'],
                            'value' => function ($modelCommentSearch) {
                                /* @var $modelCommentSearch \common\models\search\CommentSearch */
                                if (Yii::$app->user->can('user/manage/view-user')) {
                                    return $modelCommentSearch->user->email;
                                }
                                return Yii::t('app', 'Нет доступа');
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'contentOptions' => [
                                'class' => 'text-center vcenter',
                                //'style' => 'max-width: 100px !important; width: 100px !important;'
                            ],
                            'headerOptions'   => ['class' => 'text-center'],
                            'value' => function ($modelCommentSearch) {
                                /* @var $modelCommentSearch \common\models\search\CommentSearch */
                                return $modelCommentSearch->statusItem;
                            },
                            'filter' => Html::activeDropDownList($modelCommentSearch, 'status', $modelCommentSearch->statusList, [
                                'class'  => 'form-control selectpicker',
                                'data' => [
                                    'style' => 'btn-default',
                                    'live-search' => 'false',
                                    'title' => '---'
                                ]]),
                        ],
                        [
                            'attribute' => 'text',
                            'format' => 'raw',
                            'contentOptions' => [
                                //'class' => 'vcenter',
                                //'style' => 'max-width: 100px !important; width: 100px !important;'
                            ],
                            'headerOptions'   => ['class' => 'text-center'],
                            'value' => function ($modelCommentSearch) {
                                /* @var $modelCommentSearch \common\models\search\CommentSearch */
                                return $modelCommentSearch->text;
                            },
                        ],
                    ],
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
        <?php
        $js = <<< JS
        $('.selectpicker').selectpicker({});
        $('[data-toggle="tooltip-comment"]').tooltip({ boundary: 'window' });
JS;
        $this->registerJs($js); ?>
    </div>
</div>
<?php Pjax::end(); ?>