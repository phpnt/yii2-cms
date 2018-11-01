<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 25.08.2018
 * Time: 22:05
 */

use yii\bootstrap\Modal;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
?>
<?php
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-md',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">'.Yii::t('app', 'Просмотр папки').'</h2>',
    'clientOptions' => ['show' => true],
    'options' => [
        ''
    ],
]);
?>
    <div class="row">
        <div class="col-md-12">
            <?= DetailView::widget([
                'model' => $modelDocumentForm,
                'attributes' => [
                    [
                        'attribute' => 'id',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelDocumentForm) {
                            /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                            return $modelDocumentForm->id;
                        }, $modelDocumentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelDocumentForm) {
                            /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                            return $modelDocumentForm->name;
                        }, $modelDocumentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'title',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelDocumentForm) {
                            /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                            return $modelDocumentForm->title;
                        }, $modelDocumentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'route',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelDocumentForm) {
                            /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                            return $modelDocumentForm->route;
                        }, $modelDocumentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelDocumentForm) {
                            /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                            return Yii::$app->formatter->asDate($modelDocumentForm->created_at);
                        }, $modelDocumentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelDocumentForm) {
                            /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                            return Yii::$app->formatter->asDate($modelDocumentForm->updated_at);
                        }, $modelDocumentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'created_by',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelDocumentForm) {
                            /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                            return $modelDocumentForm->createdBy->email;
                        }, $modelDocumentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'updated_by',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelDocumentForm) {
                            /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                            return $modelDocumentForm->updatedBy->email;
                        }, $modelDocumentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'Просмотров'),
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelDocumentForm) {
                            /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                            return $modelDocumentForm->viewedDocument;
                        }, $modelDocumentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'Лайков'),
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelDocumentForm) {
                            /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                            return $modelDocumentForm->likedDocument;
                        }, $modelDocumentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    /*'title',
                    'meta_keywords:ntext',
                    'meta_description:ntext',
                    'annotation:ntext',
                    'content:ntext',
                    'image',
                    'status',
                    'is_folder',
                    'parent_id',
                    'template_id',*/
                    /*'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'position',*/
                ],
            ]) ?>
        </div>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
