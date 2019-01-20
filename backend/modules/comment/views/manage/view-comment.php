<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.01.2019
 * Time: 13:51
 */

use yii\bootstrap\Modal;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $modelCommentForm \common\models\forms\CommentForm */
?>
<?php
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-md',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">'.Yii::t('app', 'Просмотр комментария').'</h2>',
    'clientOptions' => ['show' => true],
    'options' => [
        ''
    ],
]);
?>
    <div class="row">
        <div class="col-md-12">
            <?= DetailView::widget([
                'model' => $modelCommentForm,
                'attributes' => [
                    [
                        'attribute' => 'id',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelCommentForm) {
                            /* @var $modelCommentForm \common\models\forms\CommentForm */
                            return $modelCommentForm->id;
                        }, $modelCommentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'text',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelCommentForm) {
                            /* @var $modelCommentForm \common\models\forms\CommentForm */
                            return $modelCommentForm->text;
                        }, $modelCommentForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();