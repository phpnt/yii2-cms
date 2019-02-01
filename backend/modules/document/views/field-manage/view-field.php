<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 24.09.2018
 * Time: 10:07
 */

use yii\bootstrap\Modal;
use yii\widgets\DetailView;
use common\models\Constants;

/* @var $this yii\web\View */
/* @var $modelFieldForm \common\models\forms\FieldForm */
?>
<?php
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-md',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">'.Yii::t('app', 'Просмотр поля').'</h2>',
    'clientOptions' => ['show' => true],
    'options' => [
        ''
    ],
]);
?>
    <div class="row">
        <div class="col-md-12">
            <?= DetailView::widget([
                'model' => $modelFieldForm,
                'attributes' => [
                    [
                        'attribute' => 'id',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->id;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->name;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'type',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->typeItem;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'label' => 'Значения',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            $string = '';
                            if (isset($modelFieldForm->valueStrings)) {
                                foreach ($modelFieldForm->valueStrings as $modelValueStringForm) {
                                    $string .= $modelValueStringForm->value.'<br>';
                                }
                            }
                            return $string;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'min_val',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->min_val;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'max_val',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->max_val;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'error_value',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->error_value;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'min_str',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->min_str;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'max_str',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->max_str;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'error_length',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->error_length;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'is_required',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            if ($modelFieldForm->is_required) {
                                return Yii::t('app', 'Да');
                            }
                            return Yii::t('app', 'Нет');
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'error_required',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->error_required;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'is_unique',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            if ($modelFieldForm->is_unique) {
                                return Yii::t('app', 'Да');
                            }
                            return Yii::t('app', 'Нет');
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'error_unique',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->error_unique;
                        }, $modelFieldForm),
                        'captionOptions' => [
                            'style' => 'width: 50% !important;'
                        ]
                    ],
                    [
                        'attribute' => 'template_id',
                        'format' => 'raw',
                        'value' => call_user_func(function ($modelFieldForm) {
                            /* @var $modelFieldForm \common\models\forms\FieldForm */
                            return $modelFieldForm->template->name;
                        }, $modelFieldForm),
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