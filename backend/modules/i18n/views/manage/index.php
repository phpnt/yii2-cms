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
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel \common\models\search\SourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Управление I18n');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="i18n-manage-index">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <?= Html::a(Yii::t('app', 'Экспорт I18n в CSV'),
                    Url::to(['/csv-manager/export',
                        'models[0]' => \common\models\search\MessageSearch::class,
                        'models[1]' => \common\models\search\SourceMessageSearch::class,
                        'with_header' => true
                    ]),
                    ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'I18n') ?></h3><br>
                <p class="m-t-sm">
                <?= Html::a(Yii::t('app', 'Поиск новых сообщений'), Url::to(['/i18n/manage/rescan']), ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'Очистить кеш'), Url::to(['/i18n/manage/clear-cache']), ['class' => 'btn btn-warning']) ?>
                </p>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'filterModel' => $searchModel,
                    'dataProvider' => $dataProvider,
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
                            'attribute' => 'id',
                            'label' => 'ID',
                            'headerOptions' => [
                                'width' => '30',
                            ],
                            'contentOptions' => [
                                'class' => 'text-align-center',
                                'style' => 'width: 30px !important;'
                            ],
                            'value' => function ($modelSourceMessageForm, $key, $index, $column) {
                                /* @var $modelSourceMessageForm \common\models\forms\SourceMessageForm */
                                return $modelSourceMessageForm->id;
                            },
                            'visible' => true,
                        ],
                        [
                            'attribute' => 'message',
                            'format' => 'raw',
                            'contentOptions' => [
                                'class' => 'source-message',
                                'style' => 'width: 400px !important;'
                            ],
                            'value' => function ($modelSourceMessageForm, $key, $index, $column) {
                                /* @var $modelSourceMessageForm \common\models\forms\SourceMessageForm */
                                return $this->render('_source-message-content', [
                                    'modelSourceMessageForm'     => $modelSourceMessageForm,
                                    'key'       => $key,
                                    'index'     => $index,
                                    'column'    => $column,
                                ]);
                            },
                        ],
                        [
                            'attribute' => 'translation',
                            'label' => Yii::t('app', 'Перевод'),
                            'contentOptions' => [
                                'class' => 'translation-tabs tabs-mini',
                            ],
                            'value' => function ($modelSourceMessageForm, $key, $index, $column) {
                                /* @var $modelSourceMessageForm \common\models\forms\SourceMessageForm */
                                return $this->render('_message-tabs', [
                                    'modelSourceMessageForm'     => $modelSourceMessageForm,
                                    'key'       => $key,
                                    'index'     => $index,
                                    'column'    => $column,
                                ]);
                            },
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'translation',
                                [0 => Yii::t('app', 'Все'), 1 => Yii::t('app', 'Не переведенные.')],
                                [
                                'class'  => 'form-control selectpicker',
                                'data' => [
                                    'style' => 'btn-default',
                                    'live-search' => 'false',
                                    'title' => '---'
                                ]]),
                        ],
                        [
                            'attribute' => 'category',
                            'headerOptions' => [
                                'width' => '120',
                            ],
                            'contentOptions' => [
                                'class' => 'text-align-center',
                            ],
                            'value' => function ($modelSourceMessageForm, $key, $index, $column) {
                                /* @var $modelSourceMessageForm \common\models\forms\SourceMessageForm */
                                return $modelSourceMessageForm->category;
                            },
                            'filter' => $searchModel::getCategories(),
                            'filterInputOptions' => [
                                'class'       => 'form-control selectpicker show-tick',
                                'data' => [
                                    'style' => 'btn-primary',
                                    'title' => Yii::t('app', 'Категория'),
                                ]
                            ],
                        ],
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{save}',
                            'headerOptions' => [
                                'width' => '40',
                            ],
                            'buttons' => [
                                'save' => function ($url, $model, $key) {
                                    if (Yii::$app->user->can('i18n/manage/save')) {
                                        return Html::a('<span class="fas fa-2x fa-save">', 'javascript:void(0);', [
                                            'class' => 'text-success',
                                            'onclick' => '
                                            $.pjax({
                                                type: "POST",
                                                url: "'.$url.'",
                                                data: jQuery("#translationsForm-'.$key.'").serialize(),
                                                container: "#translationGrid-'.$key.'",
                                                push: false,
                                                scrollTo: false
                                            })
                            '
                                        ]);
                                    } else {
                                        return Html::button('<span class="fas fa-2x fa-save">', [
                                            'class' => 'btn btn-default disabled',
                                            'disabled' => true
                                            ]);
                                    }
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>
</div>
