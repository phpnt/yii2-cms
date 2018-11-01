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
/* @var $allGeoRegionSearch common\models\search\GeoRegionSearch */
/* @var $dataProviderGeoRegionSearch yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Управление регионами');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="geo-region-form-index">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <?= Html::a(Yii::t('app', 'Экспорт регионов в CSV'),
                    Url::to(['/csv-manager/export',
                        'models[0]' => \common\models\search\GeoRegionSearch::class,
                        'with_header' => true
                    ]),
                    ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Регионы') ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProviderGeoRegionSearch,
                    'filterModel' => $allGeoRegionSearch,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],

                        'id_geo_region',
                        'iso',
                        [
                            'label' => Yii::t('app', 'Регион'),
                            'format' => 'raw',
                            'contentOptions' => [
                                'class' => 'vcenter',
                                //'style' => 'max-width: 100px !important; width: 100px !important;'
                            ],
                            'value' => function ($modelGeoRegionForm) {
                                /* @var $modelGeoRegionForm \common\models\forms\GeoRegionForm */
                                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                                    return $modelGeoRegionForm->name_ru;
                                }
                                return $modelGeoRegionForm->name_en;
                            },
                        ],
                        [
                            'label' => Yii::t('app', 'Страна'),
                            'format' => 'raw',
                            'contentOptions' => [
                                'class' => 'vcenter',
                                //'style' => 'max-width: 100px !important; width: 100px !important;'
                            ],
                            'value' => function ($modelGeoRegionForm) {
                                /* @var $modelGeoRegionForm \common\models\forms\GeoRegionForm */
                                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                                    return $modelGeoRegionForm->geoCountry->name_ru;
                                }
                                return $modelGeoRegionForm->geoCountry->short_name;
                            },
                        ],
                        'timezone',
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
