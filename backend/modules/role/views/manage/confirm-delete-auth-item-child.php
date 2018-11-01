<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 13:38
 */

use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $parent string */
?>
<?php
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-sm',
    'header' => '<h2 class="text-center">' . Yii::t('app', 'Удалить наследование RBAC') . '?</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="col-xs-6 text-center">
        <?= Html::button(Yii::t('app', 'Да'), [
            'class' => 'btn btn-danger',
            'onclick' => '
                $("#universal-modal").modal("hide");
                $.pjax({
                    type: "GET",
                    url: "' . Url::to(['/role/manage/delete-auth-item-child', 'parent' => $parent]) . '", 
                    container: "#pjax-grid-auth-item-child-block",
                    timeout: 10000,
                    push: false,
                    scrollTo: false
                })'
        ]) ?>
    </div>
    <div class="col-xs-6 text-center">
        <?= Html::button(Yii::t('app', 'Нет'), [
            'class' => 'btn btn-default',
            'data-dismiss' => 'modal'
        ]) ?>
    </div>

    <div class="clearfix"></div>
<?php
Modal::end();
?>