<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 22:11
 */

use yii\bootstrap\Modal;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $modelUserForm \common\models\forms\UserForm */
?>
<?php
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-md',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">'.Yii::t('app', 'Просмотр роли или разрешения').'</h2>',
    'clientOptions' => ['show' => true],
    'options' => [
        ''
    ],
]);
?>
    <div class="row">
        <div class="col-md-12">
            <?= DetailView::widget([
                'model' => $modelUserForm,
                'attributes' => [
                    'id',
                    //'first_name',
                    //'last_name',
                    //'auth_key',
                    //'password_hash',
                    //'password_reset_token',
                    //'email_confirm_token:email',
                    'email:email',
                    //'image',
                    //'sex',
                    //'birthday',
                    //'phone',
                    //'id_geo_country',
                    //'id_geo_city',
                    //'address',
                    'status',
                    //'ip',
                    'created_at:date',
                    'updated_at:date',
                    //'login_at',
                ],
            ]) ?>
        </div>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();