<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 28.08.2018
 * Time: 22:12
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $modelTemplateSearch common\models\search\TemplateSearch */
/* @var $dataProviderTemplateSearch yii\data\ActiveDataProvider */
?>
<?php Pjax::begin([
    'id' => 'pjax-grid-templates-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<?= BootstrapNotify::widget([]) ?>
<?php if (Yii::$app->user->can('document/template-manage/create-template')): ?>
    <p>
        <?= Html::button(Yii::t('app', 'Создать шаблон'),
            [
                'class' => 'btn btn-success',
                'onclick' => '
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['template-manage/create-template']) . '",
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })'
            ]) ?>
    </p>
<?php endif; ?>
<?= GridView::widget([
    'dataProvider' => $dataProviderTemplateSearch,
    'filterModel' => $modelTemplateSearch,
    'filterUrl' => ['refresh-templates'],
    'options' => [
        'id' => 'grid-templates-block',
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
                'view' => function ($url, $modelTemplateForm, $id) {
                    /* @var $modelTemplateForm \common\models\forms\TemplateForm */
                    if (Yii::$app->user->can('document/template-manage/view-template')) {
                        return Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0);', [
                            'class' => 'text-info',
                            'title' => Yii::t('app', 'Просмотр шаблона'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/document/template-manage/view-template', 'id' => $modelTemplateForm->id]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                        ]);
                    }
                },
                'update' => function ($url, $modelTemplateForm, $id) {
                    /* @var $modelTemplateForm \common\models\forms\TemplateForm */
                    if (Yii::$app->user->can('document/template-manage/update-template')) {
                        return Html::a('<i class="fa fa-pen"></i>', 'javascript:void(0);', [
                            'class' => 'text-warning',
                            'title' => Yii::t('app', 'Изменить папку'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/document/template-manage/update-template', 'id' => $modelTemplateForm->id]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                        ]);
                    }
                },
                'delete' => function ($url, $modelTemplateForm, $id) {
                    /* @var $modelTemplateForm \common\models\forms\TemplateForm */
                    if (Yii::$app->user->can('document/template-manage/delete-template')) {
                        return Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0);', [
                            'class' => 'text-danger',
                            'title' => Yii::t('app', 'Удалить папку'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/document/template-manage/confirm-delete-template', 'id' => $modelTemplateForm->id]) . '",
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
            'attribute' => 'name',
            'format' => 'raw',
            'contentOptions' => [
                'class' => 'vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
            'value' => function ($modelTemplateForm) {
                /* @var $modelTemplateForm \common\models\forms\TemplateForm */
                return Yii::t('app', $modelTemplateForm->name);
            },
        ],
        [
            'attribute' => 'description',
            'format' => 'raw',
            'contentOptions' => [
                'class' => 'vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
            'value' => function ($modelTemplateForm) {
                /* @var $modelTemplateForm \common\models\forms\TemplateForm */
                return Yii::t('app', $modelTemplateForm->description);
            },
        ],
        [
            'attribute' => 'status',
            'format' => 'raw',
            'contentOptions' => [
                'class' => 'text-center vcenter',
                'style' => 'max-width: 130px !important; width: 130px !important;'
            ],
            'value' => function ($modelTemplateForm) {
                /* @var $modelTemplateForm \common\models\forms\TemplateForm */
                return $modelTemplateForm->getStatusItem();
            },
            'filter' => Html::activeDropDownList($modelTemplateSearch, 'status', $modelTemplateSearch->getStatusList(), [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]]),
            //'contentOptions' => ['style'=>'max-width: 20px !important; width: 20px !important;'],
        ],
        [
            'label' => Yii::t('app', 'Поля'),
            'format' => 'raw',
            'contentOptions' => function ($modelTemplateForm, $key, $index, $column){
                /* @var $modelTemplateForm \common\models\forms\TemplateForm */
                return [
                    'class' => 'vcenter'
                ];
            },
            'value' => function ($modelTemplateForm, $key, $index, $column) {
                /* @var $modelTemplateForm \common\models\forms\TemplateForm */
                return $this->render('@backend/modules/document/views/field-manage/__fields_of_template', [
                    'manyFieldForm' => $modelTemplateForm->fields,
                    'key'           => $key,
                    'index'         => $index,
                    'column'        => $column,
                ]);
            },
        ],
        [
            'label' => Yii::t('app', 'Добавить поле'),
            'format' => 'raw',
            'contentOptions' => [
                'class' => 'text-center vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
            'value' => function ($modelTemplateForm) {
                /* @var $modelTemplateForm \common\models\forms\TemplateForm */
                if (Yii::$app->user->can('document/field-manage/create-field')) {
                    return Html::a('<i class="fa fa-2x fa-plus-square"></i>', 'javascript:void(0);', [
                        'class' => 'text-success',
                        'title' => Yii::t('app', 'Добавить поле'),
                        'onclick' => '
                        $.pjax({
                            type: "GET",
                            url: "' . Url::to(['/document/field-manage/create-field', 'template_id' => $modelTemplateForm->id]) . '", 
                            container: "#pjaxModalUniversal",
                            push: false,
                            timeout: 10000,
                            scrollTo: false
                        })'
                    ]);
                }
                return Yii::t('app', 'У вас недостаточно прав.');
            },
        ],
    ],
]); ?>
<?php
$js = <<< JS
    $('.selectpicker').selectpicker({});
JS;
$this->registerJs($js); ?>
<?php Pjax::end(); ?>
