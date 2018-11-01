<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 18:32
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $allBasketSearch common\models\search\UserSearch */
/* @var $dataProviderBasketSearch yii\data\ActiveDataProvider */
?>
<?php Pjax::begin([
    'id' => 'pjax-grid-basket-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<?= BootstrapNotify::widget() ?>
<?= GridView::widget([
    'dataProvider' => $dataProviderBasketSearch,
    'filterModel' => $allBasketSearch,
    'id' => 'grid-user-block',
    'columns' => [
        ['template' => '{view}',
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => [
                //'class' => 'text-center vcenter',
                'style'=>'max-width: 20px !important; width: 20px !important;'
            ],
            'buttons' => [
                'view' => function ($url, $modelBasketForm, $id) {
                    /* @var $modelBasketForm \common\models\forms\BasketForm */
                    if (Yii::$app->user->can('document/basket/view-basket')) {
                        return Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0);', [
                            'class' => 'text-info',
                            'title' => Yii::t('app', 'Просмотр пользователя'),
                            'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/document/basket/view-basket', 'id' => $modelBasketForm->id]) . '",
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
            'attribute' => 'document_id',
            'format' => 'raw',
            'contentOptions' => [
                //'class' => 'vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
            'headerOptions'   => ['class' => 'text-center'],
            'value' => function ($modelBasketForm) {
                /* @var $modelBasketForm \common\models\forms\BasketForm */
                return $modelBasketForm->document->name;
            },
        ],
        [
            'attribute' => 'quantity',
            'format' => 'raw',
            'contentOptions' => [
                //'class' => 'vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
            'headerOptions'   => ['class' => 'text-center'],
            'value' => function ($modelBasketForm) {
                /* @var $modelBasketForm \common\models\forms\BasketForm */
                return $modelBasketForm->quantity;
            },
        ],
        [
            'attribute' => 'status',
            'format' => 'raw',
            'value' => function ($modelBasketForm) {
                /* @var $modelBasketForm \common\models\extend\BasketExtend */
                return $modelBasketForm->statusProduct;
            },
            'filter' => Html::activeDropDownList($allBasketSearch, 'status', $allBasketSearch->statusList, [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]]),
            //'contentOptions' => ['style'=>'max-width: 120px !important; width: 120px !important;'],
        ],
        [
            'attribute' => 'user_id',
            'format' => 'raw',
            'value' => function ($modelBasketForm) {
                /* @var $modelBasketForm \common\models\extend\BasketExtend */
                if (isset($modelBasketForm->user) && Yii::$app->user->can('admin')) {
                    return $modelBasketForm->user->email;
                }
                return Yii::t('app', 'Нет прав для просмотра.');
            },
            //'contentOptions' => ['style'=>'max-width: 120px !important; width: 120px !important;'],
        ],
        [
            'attribute' => 'created_at',
            'format' => 'raw',
            'value' => function ($modelBasketForm) {
                /* @var $modelBasketForm \common\models\extend\BasketExtend */
                return Yii::$app->formatter->asDate($modelBasketForm->created_at);
            },
            //'contentOptions' => ['style'=>'max-width: 120px !important; width: 120px !important;'],
        ],
        [
            'attribute' => 'ip',
            'format' => 'raw',
            'value' => function ($modelBasketForm) {
                /* @var $modelBasketForm \common\models\extend\BasketExtend */
                return $modelBasketForm->ip;
            },
            //'contentOptions' => ['style'=>'max-width: 120px !important; width: 120px !important;'],
        ],
        [
            'attribute' => 'user_agent',
            'format' => 'raw',
            'value' => function ($modelBasketForm) {
                /* @var $modelBasketForm \common\models\extend\BasketExtend */
                return $modelBasketForm->user_agent;
            },
            //'contentOptions' => ['style'=>'max-width: 120px !important; width: 120px !important;'],
        ],
    ],
]); ?>
<?php
$js = <<< JS
    $('.selectpicker').selectpicker({});
JS;
$this->registerJs($js); ?>
<?php Pjax::end(); ?>
