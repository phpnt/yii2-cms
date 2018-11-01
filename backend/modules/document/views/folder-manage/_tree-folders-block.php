<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 21.09.2018
 * Time: 10:35
 */

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\JsTreeWidget\FoldersJsTreeWidget;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $modelDocumentSearchFolder common\models\search\DocumentSearch */
/* @var $dataProviderDocumentSearchFolders yii\data\ActiveDataProvider */
/* @var $allFolders array */
?>
<?php Pjax::begin([
    'id' => 'pjax-tree-folders-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<?= BootstrapNotify::widget([]) ?>
<?php if (Yii::$app->user->can('document/folder-manage/create-folder')): ?>
    <p>
        <?= Html::button(Yii::t('app', 'Создать папку'),
            [
                'class' => 'btn btn-success',
                'onclick' => '
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['folder-manage/create-folder']) . '",
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })'
            ]) ?>
    </p>
<?php endif; ?>

<?= FoldersJsTreeWidget::widget([
    'items' => $modelDocumentSearchFolder->allFolders,
    'plugins' => [
        //"checkbox",
        "contextmenu",
        //"dnd",
        //"massload",
        //"search",
        //"sort",
        //"state",
        //"types",
        //"unique",
        //"wholerow",
        "changed",
        //"conditionalselect"
    ],
    'options' => []
]) ?>

<?php
$js = <<< JS
    $('.selectpicker').selectpicker({});
JS;
$this->registerJs($js); ?>
<?php Pjax::end(); ?>