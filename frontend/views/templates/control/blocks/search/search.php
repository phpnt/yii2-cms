<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.02.2019
 * Time: 18:20
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\widgets\TemplateOfElement\SetSearchDefaultFields;
use frontend\views\templates\control\blocks\search\assets\SearchAssets;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $modelSearch \common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $itemsMenu array Элементы меню */
/* @var $modelDocumentForm \common\models\forms\DocumentForm Выбранный элемент */
/* @var $tree array Дерево элемента */
/* @var $templateName string */

if (Yii::$app->request->get('alias_menu_item') &&
    !Yii::$app->request->get('alias_sidebar_item')) {
    $searchUrl = Url::to(['/control/default/index',
        'alias_menu_item' => Yii::$app->request->get('alias_menu_item')]);
} elseif (Yii::$app->request->get('alias_menu_item') &&
    Yii::$app->request->get('alias_sidebar_item')) {
    $searchUrl = Url::to(['/control/default/view-list',
        'alias_menu_item' => Yii::$app->request->get('alias_menu_item'),
        'alias_sidebar_item' => Yii::$app->request->get('alias_sidebar_item')]);
}

SearchAssets::register($this);
?>
<div class="block-search">
    <div class="col-xs-12">
        <h3 class="text-center m-b-md"><?= Yii::t('app', $modelSearch->parent->name) ?></h3>
        <div class="document-form-search">
            <div class="search-<?= $templateName; ?>">
                <?php $form = ActiveForm::begin([
                    'action' => $searchUrl,
                    'method' => 'get',
                ]); ?>

                <?php if (isset($modelSearch->template)): ?>
                    <div class="row">
                        <?= SetSearchDefaultFields::widget([
                            'form' => $form,
                            'model' => $modelSearch,
                        ]); ?>
                    </div>
                <?php endif; ?>

                <div class="form-group text-center">
                    <?= Html::submitButton(Yii::t('app', 'Поиск'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('app', 'Сброс'), $searchUrl, ['class' => 'btn btn-default']) ?>
                </div>

                <?php ActiveForm::end(); ?>
                <?php
                $js = <<< JS
                function addError(id, message) {
                    console.log(id, message);
                    $( id ).addClass( "has-error" );
                    $( id + " .help-block-error" ).text( message ); 
                }
JS;
                $this->registerJs($js); ?>
            </div>
        </div>
    </div>
</div>