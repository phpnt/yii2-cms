<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 28.08.2018
 * Time: 22:40
 */

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $modelTemplateForm \common\models\forms\TemplateForm */
?>
<?php
$header = $modelTemplateForm->isNewRecord ? Yii::t('app', 'Создать шаблон') : Yii::t('app', 'Изменить шаблон');
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-lg',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">' . $header . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="row">
        <?= $this->render('_form-template', [
            'modelTemplateForm' => $modelTemplateForm,
        ]); ?>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
?>