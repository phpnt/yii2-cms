<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 09.01.2019
 * Time: 12:25
 */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelProfileTemplateForm \common\widgets\TemplateOfElement\forms\ProfileTemplateForm */

use yii\bootstrap\Modal;
?>
<?php
Modal::begin([
    'id' => 'profile-modal',
    'size' => 'modal-md',
    'header' => '<h3 class="text-center m-t-zero">'.Yii::t('app', 'Заполните профиль').'</h3>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>

<?= $this->render('_form-profile', [
    'page' => $page,
    'modelProfileTemplateForm' => $modelProfileTemplateForm,
]); ?>

    <div class="clearfix"></div>
<?php
Modal::end();
?>