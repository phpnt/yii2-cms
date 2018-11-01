<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 28.10.2018
 * Time: 22:19
 */

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $key integer */
/* @var $modelSourceMessageForm \common\models\forms\SourceMessageForm  */

Pjax::begin([
    'id' => 'translationGrid-'.$key
]);
?>
<?= BootstrapNotify::widget() ?>
<?php
$form = ActiveForm::begin([
    'id' => 'translationsForm-'.$key,
    'options' => ['data-pjax' => true]
]);
$items = [];
foreach (Yii::$app->i18n->languages as $lang) {
    $message = '';
    if(isset($modelSourceMessageForm->messages[$lang]['translation'])) {
        $message = $modelSourceMessageForm->messages[$lang]['translation'];
    }
    $items[] = [
        'label' => '<b>'.strtoupper($lang).'</b>',
        'content' => Html::textarea('Messages['.$lang.']', $message, [
            'class' => 'translation-textarea form-control',
            'rows'  => 3,
        ]),
        'active' => ($lang == 'en') ? true : false,
    ];
}
echo Tabs::widget([
    'encodeLabels' => false,
    'items' => $items,
]);
ActiveForm::end();
Pjax::end();