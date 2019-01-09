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
/* @var $profile array */
/* @var $modelProfileTemplateForm \common\widgets\TemplateOfElement\forms\ProfileTemplateForm */

use yii\bootstrap\Modal;
?>
<?php
Modal::begin([
    'id' => 'users-login',
    'size' => 'modal-md',
    'header' => '<h3 class="col-md-12 text-center">'.Yii::t('app', 'Заполните профиль').'</h3>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>

    <div class="col-md-12">
        <?php p($this->viewFile); ?>
    </div>

<?= $this->render('_form-profile', [
    'page' => $page,
    'profile' => $profile,
    'modelProfileTemplateForm' => $modelProfileTemplateForm,
]); ?>

    <div class="clearfix"></div>
<?php
Modal::end();
?>