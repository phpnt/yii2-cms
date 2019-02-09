<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.01.2019
 * Time: 13:21
 */

use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $modelSearch \common\models\search\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $itemsMenu array Элементы меню */
/* @var $item array Выбранный элемент */
/* @var $tree array Дерево элемента */
/* @var $templateName string */

// Формируем "хлебные крошки"
foreach ($tree as $value) {
    if ($value['alias'] == $page['alias']) {
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('app', $value['name']),
            'url' => Url::to(['/control/default/index', 'alias_menu_item' => $page['alias']])
        ];
    } elseif ($value['alias'] == $modelSearch->parent->alias) {
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('app', $value['name']),
            'url' => Url::to(['/control/default/view-list', 'alias_menu_item' => $page['alias'], 'alias_sidebar_item' => $value['alias']])
        ];
    } else {
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('app', $value['name']),
        ];
    }
}
$this->params['breadcrumbs'][] = Yii::t('app', $modelSearch->parent->name);
?>
<div class="col-md-12">
    <div class="list-<?= $templateName; ?>">
        <?php p($this->viewFile); ?>
        <div class="row">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'id' => 'listview-' . $templateName,
                'layout' => "{summary}<div class='col-md-12'>{pager}</div>{items}<div class='col-md-12'>{pager}</div>",
                'summaryOptions' => [
                    'class' => 'col-md-12'
                ],
                'emptyTextOptions' => [
                    'class' => 'col-md-12'
                ],
                'itemView' => function ($modelDocumentForm, $key, $index, $widget) {
                    /* @var $modelDocumentForm \common\models\forms\DocumentForm */
                    if (Yii::$app->request->get('alias_menu_item')) {
                        $modelDocumentForm->alias_menu_item = Yii::$app->request->get('alias_menu_item');
                    }
                    if (Yii::$app->request->get('alias_sidebar_item')) {
                        $modelDocumentForm->alias_sidebar_item = Yii::$app->request->get('alias_sidebar_item');
                    }
                    if (Yii::$app->request->get('alias_item')) {
                        $modelDocumentForm->alias_item = Yii::$app->request->get('alias_item');
                    }
                    return $this->render('__list-view-item' ,[
                        'modelDocumentForm' => $modelDocumentForm,
                        'key' => $key,
                        'index' => $index,
                        'widget' => $widget
                    ]);
                },
            ]); ?>
        </div>
    </div>
</div>
