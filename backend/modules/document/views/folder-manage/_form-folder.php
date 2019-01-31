<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 25.08.2018
 * Time: 14:19
 */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;
use backend\assets\TranslateAsset;
use phpnt\summernote\SummernoteWidget;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
TranslateAsset::register($this);
?>
<div id="elements-form-block">
    <?php TranslateAsset::register($this) ?>
    <?php $form = ActiveForm::begin([
        'id' => 'form',
        'action' => $modelDocumentForm->isNewRecord ? Url::to(['create-folder']) : Url::to(['update-folder', 'id' => $modelDocumentForm->id]),
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="col-md-3">
        <?= $form->field($modelDocumentForm, 'name')
            ->textInput(['placeholder' => $modelDocumentForm->getAttributeLabel('name')]) ?>
    </div>

    <div class="col-md-5">
        <?= $form->field($modelDocumentForm, 'title', [
            'template' =>  '{label}
                            <div id="title-btn" class="input-group">
                                {input}
                                <span class="input-group-btn">
                                    <a class="btn btn-default repeat-name" href="#">'.Yii::t('app', 'Повторить название').'</a>
                                </span>
                            </div>
                            {hint}
                            {error}'])
            ->textInput(['placeholder' => $modelDocumentForm->getAttributeLabel('title')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($modelDocumentForm, 'alias', [
            'template' =>  '{label}
                            <div id="title-btn" class="input-group">
                                {input}
                                <span class="input-group-btn">
                                    '.ButtonDropdown::widget([
                                        'label' => Yii::t('app', 'Сформировать'),
                                        'dropdown' => [
                                            'items' => [
                                                ['label' => Yii::t('app', 'Из названия'), 'url' => '#', 'options' => ['class'=>'translate-name']],
                                                ['label' => Yii::t('app', 'Из заголовка'), 'url' => '#', 'options' => ['class'=>'translate-title']],
                                            ],
                                        ],
                                        'options' => ['class'=>'btn-default']]).'
                                </span>
                            </div>
                            {hint}
                            {error}'])
            ->textInput(['placeholder' => $modelDocumentForm->getAttributeLabel('alias')]) ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-4">
        <?= $form->field($modelDocumentForm, 'access')->dropDownList($modelDocumentForm->accessList,
            [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]
            ]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($modelDocumentForm, 'status')->dropDownList($modelDocumentForm->statusList,
            [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]
            ]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($modelDocumentForm, 'template_id')->dropDownList($modelDocumentForm->templatesList,
            [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]
            ]) ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-12">
        <?/*= $form->field($modelDocumentForm, 'parent_id')->dropDownList($modelDocumentForm->parentsList,
            [
                'class'  => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-default',
                    'live-search' => 'false',
                    'title' => '---'
                ]
            ]) */?>
    </div>

    <?php if ($modelDocumentForm->parent_id != null): ?>
        <div class="col-md-12">
            <?= $form->field($modelDocumentForm, 'position')->dropDownList($modelDocumentForm->positionsList,
                [
                    'class'  => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-default',
                        'live-search' => 'false',
                        'title' => '---'
                    ]
                ]) ?>
        </div>
    <?php endif; ?>

    <div class="clearfix"></div>

    <div class="col-md-6">
        <?= $form->field($modelDocumentForm, 'annotation')->widget(SummernoteWidget::class,[
            'options' => [
                'id' => 'summernote-annotation-' . $modelDocumentForm->id,
                'class' => 'hidden',
            ],
            'i18n' => true,             // переводить на другие языки
            'codemirror' => true,       // использовать CodeMirror (оформленный редактор кода)
            'emoji' => true,            // включить эмоджи
            'widgetOptions' => [
                /* Настройка панели */
                'placeholder' => Yii::t('app', 'Введите текст'),
                'height' => 200,
                'tabsize' => 2,
                'minHeight' => 200,
                'maxHeight' => 200,
                'focus' => false,
                'dialogsInBody' => true,
                'enterHtml' => '<div><br></div>',
                /* Панель управления */
                'toolbar' => [
                    ['font', ['fontname', 'fontsize', 'color', 'forecolor', 'backcolor', 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                    ['insert', ['picture', 'link', 'video', 'table', 'hr']],
                    ['para', ['style', 'ol', 'ul', 'paragraph', 'height']],
                    ['misc', ['fullscreen', 'codeview', 'undo', 'redo', 'help']],
                ],
                'callbacks' => [
                    'onImageUpload' => new \yii\web\JsExpression(
                        'function (images) {
                            uploadImage(images, "#summernote-annotation-' . $modelDocumentForm->id .'", "' . Yii::$app->user->id . '-annotation" );
                        }'
                    ),
                ],
            ],
        ])->label(false); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelDocumentForm, 'content')->widget(SummernoteWidget::class,[
            'options' => [
                'id' => 'summernote-content-' . $modelDocumentForm->id,
                'class' => 'hidden',
            ],
            'i18n' => true,             // переводить на другие языки
            'codemirror' => true,       // использовать CodeMirror (оформленный редактор кода)
            'emoji' => true,            // включить эмоджи
            'widgetOptions' => [
                /* Настройка панели */
                'placeholder' => Yii::t('app', 'Введите текст'),
                'height' => 200,
                'tabsize' => 2,
                'minHeight' => 200,
                'maxHeight' => 200,
                'focus' => false,
                'dialogsInBody' => true,
                'enterHtml' => '<div><br></div>',
                /* Панель управления */
                'toolbar' => [
                    ['font', ['fontname', 'fontsize', 'color', 'forecolor', 'backcolor', 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                    ['insert', ['picture', 'link', 'video', 'table', 'hr']],
                    ['para', ['style', 'ol', 'ul', 'paragraph', 'height']],
                    ['misc', ['fullscreen', 'codeview', 'undo', 'redo', 'help']],
                ],
                'callbacks' => [
                    'onImageUpload' => new \yii\web\JsExpression(
                        'function (images) {
                            uploadImage(images, "#summernote-content-' . $modelDocumentForm->id .'", "' . Yii::$app->user->id . '-content" );
                        }'
                    ),
                ],
            ],
        ])->label(false); ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-6">
        <?= $form->field($modelDocumentForm, 'meta_keywords')
            ->textarea(['placeholder' => $modelDocumentForm->getAttributeLabel('meta_keywords')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($modelDocumentForm, 'meta_description')
            ->textarea(['placeholder' => $modelDocumentForm->getAttributeLabel('meta_keywords')]) ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-12">
        <?= $form->field($modelDocumentForm, 'parent_id')->hiddenInput()
            ->label(false) ?>
    </div>

    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary text-uppercase']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $url_frontend = Yii::$app->frontendUrl->url;
    $url_upload = Url::to(['/image/upload-summernote']);

    $js = <<< JS
       function uploadImage(images, id, title) {
            console.log(images);
            var data = new FormData();
            var ins = images.length;
            for (var x = 0; x < ins; x++) {
                data.append("ImageForm[images][]", images[x]);
            }
            data.append("ImageForm[title]", title);
            $.ajax({
                url: "$url_upload",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "post",
                success: function(data) {
                    console.log(data.urls);
                    var i = 0;
                        //console.log(element);
                    data.urls.forEach(function(element) {
                        console.log(element);
                        var imageLoad = $('<img style="width: 100%;">').attr('src', "$url_frontend" + element);
                        $(id).summernote("insertNode", imageLoad[0]);
                    });
                    $(".modal").css("overflow", "auto");
                },
                error: function(data) {
                    console.log(data);
                }
            });
       } 
JS;
    $this->registerJs($js, $this::POS_HEAD);

    $url_refresh = Url::to(['refresh-folders']);
    $id_grid_refresh = '#pjax-tree-folders-block';

    $js = <<< JS
        $('.repeat-name').click(function(){
            var text = $('#documentform-name').val();
            $('#documentform-title').val(text);
        });
        $('.translate-name').click(function(){
            var text = $('#documentform-name').val();
            var text = text.toLowerCase();
            result = translit(text);
            $('#documentform-alias').val(result);
        });
        $('.translate-title').click(function(){
            var text = $('#documentform-title').val();
            var text = text.toLowerCase();
            result = translit(text);
            $('#documentform-alias').val(result);
        });
        $('.selectpicker').selectpicker({});
        $('#form').on('beforeSubmit', function () {
            var text = $('#documentform-alias').val();
            var text = text.toLowerCase();
            result = translit(text);
            $('#documentform-alias').val(result);
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
