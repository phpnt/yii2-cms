<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Менеджер пользователей');
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $allUserSearch common\models\search\UserSearch */
/* @var $dataProviderUserSearch yii\data\ActiveDataProvider */
?>
<div class="user-default-index">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <?= Html::a(Yii::t('app', 'Экспорт пользователей, их ролей и соц. ключей в CSV'),
                    Url::to(['/csv-manager/export',
                        'models[0]' => \common\models\search\UserSearch::class,
                        'models[1]' => \common\models\search\AuthAssignmentSearch::class,
                        'models[2]' => \common\models\search\UserOauthKeySearch::class,
                        'with_header' => true
                    ]),
                    ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Пользователи') ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->render('_grid-user-block', [
                    'allUserSearch' => $allUserSearch,
                    'dataProviderUserSearch' => $dataProviderUserSearch
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </div>
</div>
