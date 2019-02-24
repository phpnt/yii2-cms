<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $modelTemplateSearch common\models\search\TemplateSearch */
/* @var $dataProviderTemplateSearch yii\data\ActiveDataProvider */
/* @var $modelDocumentSearchFolder common\models\search\DocumentSearch */
/* @var $dataProviderDocumentSearchFolders yii\data\ActiveDataProvider */
/* @var $modelFolders array */

$this->title = Yii::t('app', 'Менеджер документов');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-manage-index">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <?= Html::a(Yii::t('app', 'Экспорт всех шаблонов и документов в CSV'),
                    Url::to(['/csv-manager/export',
                        'models[0]' => \common\models\search\DocumentSearch::class,
                        'models[1]' => \common\models\search\TemplateSearch::class,
                        'models[2]' => \common\models\search\FieldSearch::class,
                        'models[3]' => \common\models\search\ValueIntSearch::class,
                        'models[4]' => \common\models\search\ValueNumericSearch::class,
                        'models[5]' => \common\models\search\ValueStringSearch::class,
                        'models[6]' => \common\models\search\ValueTextSearch::class,
                        'models[7]' => \common\models\search\ValuePriceSearch::class,
                        'models[8]' => \common\models\search\TemplateViewSearch::class,
                        'with_header' => true
                    ]),
                    ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Yii::t('app', 'Папки') ?></h3>
                        <div class="box-tools pull-right">
                            <span data-toggle="tooltip-folder" title="" class="btn btn-box-tool" data-original-title="
                            <?= Yii::t('app', 'Таблица ‘document’ документы. Папки CMS. Отображает основную структуру сайта. Нажатие правой кнопки мыши на выбранной папки – для создания, редактирования, просмотра, удаления этой папки. Нажатие левой кнопки мыши на выбранной папки – для создания, редактирования, просмотра, удаления элементов этой папки.'); ?>
                            "><i class="fas fa-question"></i>
                            </span>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <?= $this->render('@backend/modules/document/views/folder-manage/_tree-folders-block', [
                            'modelDocumentSearchFolder' => $modelDocumentSearchFolder,
                        ]); ?>
                    </div>
                    <div class="box-footer">

                    </div>
                </div>
            </div>
        </div>
        <?php
        $js = <<< JS
        $('[data-toggle="tooltip-folder"]').tooltip({ boundary: 'window' })
JS;
        $this->registerJs($js); ?>
    </div>
    <div class="col-md-7">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Документы') ?></h3>
                <div class="box-tools pull-right">
                    <span data-toggle="tooltip-element" title="" class="btn btn-box-tool" data-original-title="
                    <?= Yii::t('app', 'Таблица ‘document’ документы. Элементы определенной папки. Это могут быть публикации, товары, шаблоны писем или собственные элементы, используемые на сайте. Чтобы создать, редактировать, просматривать, удалять элементы, выберите папку.'); ?>
                    "><i class="fas fa-question"></i>
                    </span>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->render('@backend/modules/document/views/element-manage/_grid-elements-block', [
                    'modelDocumentForm' => null,
                    'modelDocumentSearch' => null,
                    'dataProviderDocumentSearch' => null
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
        <?php
        $js = <<< JS
        $('[data-toggle="tooltip-element"]').tooltip({ boundary: 'window' })
JS;
        $this->registerJs($js); ?>
    </div>
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Шаблоны') ?></h3>
                <div class="box-tools pull-right">
                    <span data-toggle="tooltip-template" title="" class="btn btn-box-tool" data-original-title="
                    <?= Yii::t('app', 'Таблица ‘template’ шаблоны. содержит шаблоны для элементов. Шаблоны назначаются папкам и применяются к элементам этих папок.'); ?>
                    "><i class="fas fa-question"></i>
                    </span>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= $this->render('@backend/modules/document/views/template-manage/_grid-templates-block', [
                    'modelTemplateSearch' => $modelTemplateSearch,
                    'dataProviderTemplateSearch' => $dataProviderTemplateSearch
                ]); ?>
            </div>
            <div class="box-footer">

            </div>
        </div>
        <?php
        $js = <<< JS
        $('[data-toggle="tooltip-template"]').tooltip({ boundary: 'window' })
JS;
        $this->registerJs($js); ?>
    </div>
</div>
