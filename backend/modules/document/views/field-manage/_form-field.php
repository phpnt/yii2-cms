<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 24.09.2018
 * Time: 5:21
 */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use phpnt\ICheck\ICheck;
use common\models\Constants;
use phpnt\datepicker\BootstrapDatepicker;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelFieldForm \common\models\forms\FieldForm */
?>
<div id="elements-form-block">
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => $modelFieldForm->isNewRecord ? Url::to(['field-manage/create-field', 'template_id' => $modelFieldForm->template_id]) : Url::to(['field-manage/update-field', 'template_id' => $modelFieldForm->template_id, 'id' => $modelFieldForm->id]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="col-md-6">
        <?= $form->field($modelFieldForm, 'name')
            ->textInput(['placeholder' => $modelFieldForm->getAttributeLabel('name')]) ?>
    </div>

    <div class="col-md-6">
        <?php if ($modelFieldForm->isNewRecord): ?>
            <?= $form->field($modelFieldForm, 'type')->dropDownList($modelFieldForm->typeList,
                [
                    'class'  => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-default',
                        'live-search' => 'false',
                        'title' => '---',
                        'size' => 10
                    ],
                    'onchange' => '
                        $.pjax({
                            type: "POST", 
                            url: "'.Url::to(['field-manage/refresh-field-form']).'",
                            data: $("#form").serializeArray(),
                            container: "#elements-form-block",
                            push: false,
                            timeout: 10000,
                            scrollTo: false
                        });
                '
                ]) ?>
        <?php else: ?>
            <?= $form->field($modelFieldForm, 'type')->dropDownList($modelFieldForm->typeList,
                [
                    'class'  => 'form-control selectpicker disabled',
                    'disabled' => true,
                ]) ?>

        <?php endif; ?>
    </div>

    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_INT ||
        $modelFieldForm->type == Constants::FIELD_TYPE_FLOAT
    ): ?>
        <div class="col-md-6">
            <?= $form->field($modelFieldForm, 'min')
                ->textInput(['placeholder' => $modelFieldForm->getAttributeLabel('min')]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($modelFieldForm, 'max')
                ->textInput(['placeholder' => $modelFieldForm->getAttributeLabel('max')]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_required', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_unique', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
    <?php endif; ?>

    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_STRING): ?>
        <div class="col-md-12">
            <?= $form->field($modelFieldForm, 'max')
                ->textInput(['placeholder' => $modelFieldForm->getAttributeLabel('max')]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_required', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_unique', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
    <?php endif; ?>

    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_INT_RANGE ||
        $modelFieldForm->type == Constants::FIELD_TYPE_FLOAT_RANGE
    ): ?>
        <div class="col-md-6">
            <?= $form->field($modelFieldForm, 'min')
                ->textInput(['placeholder' => $modelFieldForm->getAttributeLabel('min')]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($modelFieldForm, 'max')
                ->textInput(['placeholder' => $modelFieldForm->getAttributeLabel('max')]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_required', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
    <?php endif; ?>

    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_TEXT ||
        $modelFieldForm->type == Constants::FIELD_TYPE_EMAIL ||
        $modelFieldForm->type == Constants::FIELD_TYPE_URL ||
        $modelFieldForm->type == Constants::FIELD_TYPE_SOCIAL ||
        $modelFieldForm->type == Constants::FIELD_TYPE_YOUTUBE
    ): ?>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_required', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_unique', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
    <?php endif; ?>

    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_DATE): ?>
        <div class="col-md-6">
            <?= $form->field($modelFieldForm, 'input_date_from')->widget(BootstrapDatepicker::className(), [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy',
                'language'  => Yii::$app->language
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($modelFieldForm, 'input_date_to')->widget(BootstrapDatepicker::className(), [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy',
                'language'  => Yii::$app->language
            ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_required', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
    <?php endif; ?>

    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_DATE_RANGE): ?>
        <div class="col-md-6">
            <?= $form->field($modelFieldForm, 'input_date_from')->widget(BootstrapDatepicker::className(), [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy',
                'language'  => Yii::$app->language
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($modelFieldForm, 'input_date_to')->widget(BootstrapDatepicker::className(), [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy',
                'language'  => Yii::$app->language
            ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_required', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
    <?php endif; ?>

    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_FILE): ?>
        <div class="col-md-12">
            <?php $modelFieldForm->file_extensions = $modelFieldForm->getFileExtValues(); ?>
            <?= $form->field($modelFieldForm, 'file_extensions')->dropDownList($modelFieldForm->getFileExtList(),
                [
                    'class'  => 'form-control selectpicker',
                    'multiple' => 'true',
                    'data' => [
                        'style' => 'btn-default',
                        'live-search' => 'false',
                        'title' => '---',
                        'size' => 10
                    ]
                ])->hint('<i>'.Yii::t('app', Yii::t('app', 'Если не указано ни одного раширения, доступны для загрузки все.')).'</i>') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_required', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
    <?php endif; ?>

    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_FEW_FILES): ?>
        <div class="col-md-12">
            <?php $modelFieldForm->file_extensions = $modelFieldForm->getFileExtValues(); ?>
            <?= $form->field($modelFieldForm, 'file_extensions')->dropDownList($modelFieldForm->getFileExtList(),
                [
                    'class'  => 'form-control selectpicker',
                    'multiple' => 'true',
                    'data' => [
                        'style' => 'btn-default',
                        'live-search' => 'false',
                        'title' => '---',
                        'size' => 10
                    ]
                ])->hint('<i>'.Yii::t('app', Yii::t('app', 'Если не указано ни одного раширения, доступны для загрузки все.')).'</i>') ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($modelFieldForm, 'max')
                ->textInput(['placeholder' => $modelFieldForm->getAttributeLabel('max')]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_required', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
    <?php endif; ?>

    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_PRICE ||
        $modelFieldForm->type == Constants::FIELD_TYPE_ADDRESS ||
        $modelFieldForm->type == Constants::FIELD_TYPE_CITY ||
        $modelFieldForm->type == Constants::FIELD_TYPE_REGION ||
        $modelFieldForm->type == Constants::FIELD_TYPE_COUNTRY
    ): ?>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_required', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
    <?php endif; ?>

    <?php if ($modelFieldForm->type == Constants::FIELD_TYPE_CHECKBOX ||
        $modelFieldForm->type == Constants::FIELD_TYPE_RADIO ||
        $modelFieldForm->type == Constants::FIELD_TYPE_LIST ||
        $modelFieldForm->type == Constants::FIELD_TYPE_LIST_MULTY): ?>
        <div class="col-md-12">
            <h4><?= Yii::t('app', 'Значения') ?></h4>
            <div class="list-wrapper">
                <?php if (empty($modelFieldForm->list)) : ?>
                    <?= $form->field($modelFieldForm, 'item', [
                        'options' => ['class' => 'form-group list-item'],
                        'template' =>  '
                            <div class="input-group control-group after-add-more">
                                {input}
                                <div class="input-group-btn">
                                    <button class="btn btn-success add-item" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <i>{hint}</i>
                            {error}'])
                        ->textInput([
                            'id' => 'fieldform-item-0',
                            'name' => 'FieldForm[list][0]',
                        ]) ?>
                <?php else : ?>
                    <?php $i = 0; ?>
                    <?php foreach ($modelFieldForm->list as $item): ?>
                        <?php
                        if ($i == 0) {
                            $button = '<button class="btn btn-success add-item" type="button"><i class="fa fa-plus"></i></button>';
                        } else {
                            $button = '<button class="btn btn-danger remove-item" type="button"><i class="fa fa-times"></i></button>';
                        }
                        ?>
                        <div class="form-group list-item field-fieldform-item-<?= $i ?>">
                            <div class="input-group control-group after-add-more">
                                <input type="text" value="<?= $item ?>" id="fieldform-item-<?= $i ?>" class="form-control" name="FieldForm[list][<?= $i ?>]">
                                <div class="input-group-btn"><?= $button ?></div>
                            </div>
                            <i></i>
                            <p class="help-block help-block-error"></p>
                        </div>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-3">
            <?= $form->field($modelFieldForm, 'is_required', ['template' => '{label} {input}'])->widget(ICheck::className(), [
                'type'  => ICheck::TYPE_CHECBOX,
                'style'  => ICheck::STYLE_FLAT,
                'color'  => 'blue'                  // цвет
            ])->label(false) ?>
        </div>
    <?php endif; ?>

    <div class="clearfix"></div>

    <div class="col-md-12">
        <?= $form->field($modelFieldForm, 'template_id')->hiddenInput([])->error(false)->label(false) ?>
    </div>

    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary text-uppercase']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php
    $url_refresh = Url::to(['/document/field-manage/refresh-fields', 'template_id' => $modelFieldForm->template_id]);
    $id_grid_refresh = '#field_of_template_' . $modelFieldForm->template_id;

    $js = <<< JS
        $('.selectpicker').selectpicker({});
        $('#form').on('beforeSubmit', function () { 
            var form = $(this);
                $.pjax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: new FormData($('#form')[0]),
                    container: "#elements-form-block",
                    push: false,
                    scrollTo: false,
                    cache: false,
                    contentType: false,
                    timeout: 10000,
                    processData: false
                })
                .done(function(data) {
                    try {
                        var result = jQuery.parseJSON(data);
                    } catch (e) {
                        return false;
                    }
                    if(result.success) {
                        // data is saved
                        console.log('success');
                        $("#universal-modal").modal("hide");
                        $.pjax({
                            type: "GET", 
                            url: "$url_refresh",
                            container: "$id_grid_refresh",
                            push: false,
                            timeout: 20000,
                            scrollTo: false
                        });
                    } else if (result.validation) {
                        // server validation failed
                        console.log('validation failed');
                        form.yiiActiveForm('updateMessages', data.validation, true); // renders validation messages at appropriate places
                    } else {
                        // incorrect server response
                        console.log('incorrect server response');
                    }
                })
                .fail(function () {
                    // request failed
                    console.log('request failed');
                })
            return false; // prevent default form submission
        });
JS;
    $this->registerJs($js); ?>
</div>
