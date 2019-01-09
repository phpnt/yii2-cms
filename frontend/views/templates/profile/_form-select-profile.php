<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 09.01.2019
 * Time: 12:21
 */

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $page array */
/* @var $modelUserForm \common\models\forms\UserForm */
/* @var $modelProfileTemplateForm \common\widgets\TemplateOfElement\forms\ProfileTemplateForm */
/* @var $profile array */
?>
<div class="col-md-12">
    <?php p($this->viewFile); ?>
</div>
<?php $form = ActiveForm::begin([
    'id' => 'form-select-profile',
    'options' => ['data-pjax' => true]
]); ?>

<div class="col-md-12">
    <?= $form->field($modelProfileTemplateForm, 'parent_id')->dropDownList($modelProfileTemplateForm->getSelectProfile($page),
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
                    url: "'.Url::to(['select-profile']).'",
                    data: $("#form-select-profile").serializeArray(),
                    container: "#pjaxModalUniversal",
                    push: false,
                    timeout: 10000,
                    scrollTo: false
                });
                '
        ]) ?>
</div>

<?php ActiveForm::end(); ?>
