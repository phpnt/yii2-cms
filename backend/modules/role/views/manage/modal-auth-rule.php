<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 13:56
 */

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $modelAuthRuleForm \common\models\forms\AuthRuleForm */
?>
<?php
$header = $modelAuthRuleForm->isNewRecord ? Yii::t('app', 'Создать правило') : Yii::t('app', 'Изменить правило');
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-md',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">' . $header . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="row">
        <?= $this->render('_form-auth-rule', [
            'modelAuthRuleForm' => $modelAuthRuleForm,
        ]); ?>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
?>