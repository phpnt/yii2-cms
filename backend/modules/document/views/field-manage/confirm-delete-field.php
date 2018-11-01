<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 24.09.2018
 * Time: 10:17
 */

use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $id int */
/* @var $template_id int */
?>
<?php
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-sm',
    'header' => '<h2 class="text-center">' . Yii::t('app', 'Удалить поле') . '?</h2>',
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
                    url: "' . Url::to(['field-manage/delete-field', 'id' => $id]) . '",
                    container: "#field_of_template_' . $template_id . '",
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