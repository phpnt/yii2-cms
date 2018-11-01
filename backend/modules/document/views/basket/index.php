<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 18:29
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Менеджер корзины');
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $allBasketSearch common\models\search\UserSearch */
/* @var $dataProviderBasketSearch yii\data\ActiveDataProvider */
?>
<div class="document-basket-index">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <?= Html::a(Yii::t('app', 'Экспорт корзины в CSV'),
                    Url::to(['/csv-manager/export',
                        'models[0]' => \common\models\search\BasketSearch::class,
                        'with_header' => true
                    ]),
                    ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Корзина') ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->render('_grid-basket-block', [
                    'allBasketSearch' => $allBasketSearch,
                    'dataProviderBasketSearch' => $dataProviderBasketSearch,
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>
</div>