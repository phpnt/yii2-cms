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
/* @var $allAuthItemSearch common\models\search\AuthItemSearch */
/* @var $dataProviderAuthItemSearch yii\data\ActiveDataProvider */
/* @var $allAuthItemChildSearch common\models\search\AuthItemChildSearch */
/* @var $dataProviderAuthItemChildSearch yii\data\ActiveDataProvider */
/* @var $allAuthRuleSearch common\models\search\AuthRuleSearch */
/* @var $dataProviderAuthRuleSearch yii\data\ActiveDataProvider */
/* @var $modelAuthItemForm \common\models\forms\AuthItemForm */

$this->title = Yii::t('app', 'Роли и допуски');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-default-index">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <?= Html::a(Yii::t('app', 'Экспорт RBAC в CSV'),
                    Url::to(['/csv-manager/export',
                        'models[1]' => \common\models\search\AuthItemSearch::class,
                        'models[2]' => \common\models\search\AuthItemChildSearch::class,
                        'models[3]' => \common\models\search\AuthRuleSearch::class,
                        'with_header' => true
                    ]),
                    ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <?= $this->render('_grid-auth-item-block', [
            'allAuthItemSearch' => $allAuthItemSearch,
            'dataProviderAuthItemSearch' => $dataProviderAuthItemSearch,
            'modelAuthItemForm' => $modelAuthItemForm
        ]); ?>
    </div>

    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Наследование ролей') ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->render('_grid-auth-item-child-block', [
                    'allAuthItemChildSearch' => $allAuthItemChildSearch,
                    'dataProviderAuthItemChildSearch' => $dataProviderAuthItemChildSearch,
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Правила для пользователей') ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->render('_grid-auth-rule-block', [
                    'allAuthRuleSearch' => $allAuthRuleSearch,
                    'dataProviderAuthRuleSearch' => $dataProviderAuthRuleSearch,
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>
</div>
