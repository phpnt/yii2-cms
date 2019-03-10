<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.08.2018
 * Time: 6:33
 */

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $modelDocumentForm \common\models\forms\DocumentForm */
/* @var $modelDocumentSearch common\models\search\DocumentSearch */
/* @var $dataProviderDocumentSearch yii\data\ActiveDataProvider */
?>
<?php Pjax::begin([
    'id' => 'pjax-grid-elements-block',
    'timeout' => 10000,
    'enablePushState' => false,
    'options' => [
        'class' => 'min-height-250',
    ]
]); ?>
<?= $this->render('_grid-elements-block', [
    'modelDocumentForm' => $modelDocumentForm,
    'modelDocumentSearch' => $modelDocumentSearch,
    'dataProviderDocumentSearch' => $dataProviderDocumentSearch,
]) ?>
<?php Pjax::end(); ?>
