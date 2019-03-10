<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.01.2019
 * Time: 6:27
 */

use common\models\Constants;
use common\widgets\TemplateOfElement\fields\FieldHidden;

/* @var $this \yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $widget \common\widgets\TemplateOfElement\SetBasketFields */
/* @var $model \common\models\forms\DocumentForm */
/* @var $modelName string */

$form = $widget->form;
$model = $widget->model;
$valuePrice = $widget->valuePrice;
?>
<?php foreach ($model->template->fields as $modelFieldForm): ?>
    <?php /* @var $modelFieldForm \common\models\forms\FieldForm */ ?>
    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_DOC): ?>
        <?= $form->field($model, 'value_int', [
            'options' => [
                'id' => 'group-' . $model->field_id_prefix . '-' . $modelFieldForm->id
            ]
        ])->widget(FieldHidden::class, [
            'modelFieldForm' => $modelFieldForm,
            'data_id' => $model->field_id_prefix . '-' . $modelFieldForm->id,
            'options' => [
                'class' => 'form-control',
                'value' => $model->comment_id,
            ],
        ])->label(false) ?>
    <?php endif; ?>
<?php endforeach; ?>
