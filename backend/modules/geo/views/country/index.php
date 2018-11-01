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
/* @var $allGeoCountrySearch common\models\search\GeoCountrySearch */
/* @var $dataProviderGeoCountry yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Управление странами');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="geo-country-form-index">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <?= Html::a(Yii::t('app', 'Экспорт стран в CSV'),
                    Url::to(['/csv-manager/export',
                        'models[0]' => \common\models\search\GeoCountrySearch::class,
                        'with_header' => true
                    ]),
                    ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Страны') ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProviderGeoCountry,
                    'filterModel' => $allGeoCountrySearch,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],

                        'id_geo_country',
                        'continent',
                        [
                            'label' => Yii::t('app', 'Страна'),
                            'format' => 'raw',
                            'contentOptions' => [
                                'class' => 'vcenter',
                                //'style' => 'max-width: 100px !important; width: 100px !important;'
                            ],
                            'value' => function ($modelGeoCountryForm) {
                                /* @var $modelGeoCountryForm \common\models\forms\GeoCountryForm */
                                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                                    return $modelGeoCountryForm->name_ru;
                                }
                                return $modelGeoCountryForm->short_name;
                            },
                        ],
                        'lat',
                        'lon',
                        'timezone',
                        //'iso2',
                        //'short_name',
                        //'long_name',
                        //'iso3',
                        'num_code',
                        //'un_member',
                        'calling_code',
                        //'cctld',
                        'phone_number_digits',
                        'currency',
                        //'system_measure',
                        //'active',
                    ],
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>
</div>
