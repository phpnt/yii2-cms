<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 14.02.2019
 * Time: 14:45
 */

use yii\bootstrap\Modal;
use common\models\Constants;

/* @var $this yii\web\View */
/* @var $modelTemplateViewForm \common\models\forms\TemplateViewForm */
?>
<?php
switch ($modelTemplateViewForm->type) {
    case Constants::TYPE_ITEM:
        $header = Yii::t('app', 'Шаблон элемента');
        break;
    case Constants::TYPE_ITEM_LIST:
        $header = Yii::t('app', 'Шаблон элемента в списке');
        break;
    case Constants::TYPE_ITEM_BASKET:
        $header = Yii::t('app', 'Шаблон элемента в корзине');
        break;
    default:
        $header = Yii::t('app', 'Шаблон');
        break;
}
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-lg',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">' . $header . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="row">
        <?= $this->render('_form-template-view', [
            'modelTemplateViewForm' => $modelTemplateViewForm,
        ]); ?>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
?>