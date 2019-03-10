<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.08.2018
 * Time: 6:33
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $modelDocumentSearch common\models\search\DocumentSearch */
/* @var $dataProviderDocumentSearch yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Управление комментариями');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="comment-manage-index">
        <?= BootstrapNotify::widget() ?>
        <div class="col-md-12">
            <div class="box">
                <div class="box-body table-responsive">
                    <h3 class="box-title"><span class="text-folder"><i class="fa fa-folder-open"></i> </span> <strong> <?= Html::encode(Yii::t('app', $modelDocumentForm->name)) ?></strong></h3>
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderDocumentSearch,
                        'filterModel' => $modelDocumentSearch,
                        'filterUrl' => Url::to(['refresh-elements', 'id_folder' => $modelDocumentForm->id]),
                        'options' => [
                            //'id' => 'grid-element-block',
                            'class' => 'grid-view'
                        ],
                        'columns' => [
                            [
                                'template' => '{view} {update} {delete}',
                                'class' => 'yii\grid\ActionColumn',
                                'contentOptions' => [
                                    'class' => 'text-center vcenter',
                                    'style'=>'max-width: 20px !important; width: 20px !important;'
                                ],
                                'buttons' => [
                                    'view' => function ($url, $modelDocumentForm, $id) {
                                        /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                                        if (Yii::$app->user->can('elements/comments/view')) {
                                            return Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0);', [
                                                'class' => 'text-info',
                                                'title' => Yii::t('app', 'Просмотр элемента'),
                                                'onclick' => '
                                                    $.pjax({
                                                        type: "GET",
                                                        url: "' . Url::to(['view', 'id' => $modelDocumentForm->id]) . '",
                                                        container: "#pjaxModalUniversal",
                                                        push: false,
                                                        timeout: 10000,
                                                        scrollTo: false
                                                    })'
                                            ]);
                                        }
                                        return false;
                                    },
                                    'update' => function ($url, $modelDocumentForm, $id) {
                                        /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                                        if (Yii::$app->user->can('elements/comments/update')) {
                                            return Html::a('<i class="fa fa-pen"></i>', 'javascript:void(0);', [
                                                'class' => 'text-warning',
                                                'title' => Yii::t('app', 'Изменить элемент'),
                                                'onclick' => '
                                                    $.pjax({
                                                        type: "GET",
                                                        url: "' . Url::to(['update', 'id_document' => $modelDocumentForm->id, 'id_folder' => $modelDocumentForm->parent_id]) . '",
                                                        container: "#pjaxModalUniversal",
                                                        push: false,
                                                        timeout: 10000,
                                                        scrollTo: false
                                                    })'
                                            ]);
                                        }
                                        return false;
                                    },
                                    'delete' => function ($url, $modelDocumentForm, $id) {
                                        /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                                        if (Yii::$app->user->can('elements/comments/delete')) {
                                            return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                                                'class' => 'text-danger',
                                                'title' => Yii::t('app', 'Удалить элемент'),
                                                'onclick' => '
                                                    $.pjax({
                                                        type: "GET",
                                                        url: "' . Url::to(['confirm-delete', 'id' => $modelDocumentForm->id]) . '",
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
                                'class' => 'yii\grid\SerialColumn',
                                'contentOptions' => [
                                    'class' => 'text-center vcenter',
                                ],
                            ],
                            [
                                'attribute' => 'content',
                                'format' => 'raw',
                                'contentOptions' => [
                                    'class' => 'vcenter',
                                    //'style' => 'max-width: 100px !important; width: 100px !important;'
                                ],
                                'headerOptions'   => ['class' => 'text-center'],
                                'value' => function ($modelDocumentForm) {
                                    /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                                    return $modelDocumentForm->content;
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
                                'filter' => Html::activeDropDownList($modelDocumentSearch, 'status', $modelDocumentSearch->statusList, [
                                    'class'  => 'form-control selectpicker',
                                    'data' => [
                                        'style' => 'btn-default',
                                        'live-search' => 'false',
                                        'title' => '---'
                                    ]]),
                                //'contentOptions' => ['style'=>'max-width: 20px !important; width: 20px !important;'],
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'raw',
                                'contentOptions' => [
                                    'class' => 'text-center vcenter',
                                ],
                                'value' => function ($modelDocumentForm) {
                                    /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                                    return Yii::$app->formatter->asDatetime($modelDocumentForm->created_at);
                                },
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
                </div>
            </div>
        </div>
        <?php
        $js = <<< JS
    $('.selectpicker').selectpicker({});
JS;
        $this->registerJs($js); ?>
    </div>
