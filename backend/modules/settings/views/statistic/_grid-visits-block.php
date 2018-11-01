<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 01.09.2018
 * Time: 17:06
 */

use yii\grid\GridView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $allVisitSearch common\models\search\VisitSearch */
/* @var $dataProviderVisitSearch yii\data\ActiveDataProvider */
?>
<?php Pjax::begin([
    'id' => 'pjax-grid-visit-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<?= BootstrapNotify::widget() ?>
<?= GridView::widget([
    'dataProvider' => $dataProviderVisitSearch,
    //'filterModel' => $allVisitSearch,
    'id' => 'grid-visit-block',
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],
        'id',
        'created_at:date',
        [
            'attribute' => 'document_id',
            'format' => 'raw',
            'contentOptions' => [
                //'class' => 'vcenter',
                //'style' => 'max-width: 100px !important; width: 100px !important;'
            ],
            'headerOptions'   => ['class' => 'text-center'],
            'value' => function ($modelLileForm) {
                /* @var $modelLileForm \common\models\forms\LikeForm */
                return $modelLileForm->document->name;
            },
        ],
        'ip',
        'user_agent:ntext',
        'user_id',
        //['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
<?php
$js = <<< JS
    $('.selectpicker').selectpicker({});
JS;
$this->registerJs($js); ?>
<?php Pjax::end(); ?>