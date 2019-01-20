<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.08.2018
 * Time: 13:54
 */

use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $modelCommentForm \common\models\forms\CommentForm */
?>
<?php
$header = $modelCommentForm->isNewRecord ? Yii::t('app', 'Создать комментарий') : Yii::t('app', 'Изменить комментарий');
Modal::begin([
    'id' => 'universal-modal',
    'size' => 'modal-lg',
    'header' => '<h2 class="text-center m-t-sm m-b-sm">' . $header . '</h2>',
    'clientOptions' => ['show' => true],
    'options' => [],
]);
?>
    <div class="row">
        <?= $this->render('_form-comment', [
            'modelCommentForm' => $modelCommentForm,
        ]); ?>
    </div>
    <div class="clearfix"></div>
<?php
Modal::end();
?>
