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
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <?= $this->render('@backend/modules/document/views/folder-manage/_tree-folders-block', [
                            'modelDocumentSearchFolder' => $modelDocumentSearchFolder,
                        ]); ?>
                        <?/*= $this->render('@backend/modules/document/views/folder-manage/_grid-folders-block', [
                            'modelDocumentSearchFolder' => $modelDocumentSearchFolder,
                            'dataProviderDocumentSearchFolders' => $dataProviderDocumentSearchFolders
                        ]); */?>
                    </div>
                    <div class="box-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Документы') ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
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
    </div>
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('app', 'Шаблоны') ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
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
    </div>
</div>
