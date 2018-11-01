<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use yii\helpers\Url;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $allVisitSearch common\models\search\VisitSearch */
/* @var $dataProviderVisitSearch yii\data\ActiveDataProvider */
/* @var $allLikeSearch common\models\search\LikeSearch */
/* @var $dataProviderLikeSearch yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Статистика');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="settings-statistic-index">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Статистика посещений') ?></h3>
                <div class="box-tools pull-right">
                    <?= Html::a(Yii::t('app', 'Экспорт статистики посещений в CSV'),
                        Url::to(['/csv-manager/export',
                            'models[0]' => \common\models\search\VisitSearch::class,
                            'with_header' => true
                        ]),
                        ['class' => 'btn btn-box-tool', 'data-pjax' => 0]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->render('_grid-visits-block', [
                    'allVisitSearch' => $allVisitSearch,
                    'dataProviderVisitSearch' => $dataProviderVisitSearch,
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Оценка пользователей') ?></h3>
                <div class="box-tools pull-right">
                    <?= Html::a(Yii::t('app', 'Экспорт оценок пользователей в CSV'),
                        Url::to(['/csv-manager/export',
                            'models[0]' => \common\models\search\LikeSearch::class,
                            'with_header' => true
                        ]),
                        ['class' => 'btn btn-box-tool', 'data-pjax' => 0]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->render('_grid-likes-block', [
                    'allLikeSearch' => $allLikeSearch,
                    'dataProviderLikeSearch' => $dataProviderLikeSearch,
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>
</div>
