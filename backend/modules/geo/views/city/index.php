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

/* @var $this yii\web\View */
/* @var $allGeoCitySearch common\models\search\GeoCitySearch */
/* @var $dataProviderGeoCitySearch yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Управление городами');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="geo-city-form-index">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <?= Html::a(Yii::t('app', 'Экспорт городов в CSV'),
                    Url::to(['/csv-manager/export',
                        'models[0]' => \common\models\search\GeoCitySearch::class,
                        'with_header' => true
                    ]),
                    ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Города') ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProviderGeoCitySearch,
                    'filterModel' => $allGeoCitySearch,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],

                        'id_geo_city',
                        [
                            'label' => Yii::t('app', 'Город'),
                            'format' => 'raw',
                            'contentOptions' => [
                                'class' => 'vcenter',
                                //'style' => 'max-width: 100px !important; width: 100px !important;'
                            ],
                            'value' => function ($modelGeoCityForm) {
                                /* @var $modelGeoCityForm \common\models\forms\GeoCityForm */
                                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                                    return $modelGeoCityForm->name_ru;
                                }
                                return $modelGeoCityForm->name_en;
                            },
                        ],
                        [
                            'attribute' => 'id_geo_region',
                            'format' => 'raw',
                            'contentOptions' => [
                                'class' => 'vcenter',
                                //'style' => 'max-width: 100px !important; width: 100px !important;'
                            ],
                            'value' => function ($modelGeoCityForm) {
                                /* @var $modelGeoCityForm \common\models\forms\GeoCityForm */
                                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                                    return $modelGeoCityForm->geoRegion->name_ru;
                                }
                                return $modelGeoCityForm->geoRegion->name_en;
                            },
                        ],
                        'lat',
                        'lon',
                        //'okato',

                        //['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>
</div>

