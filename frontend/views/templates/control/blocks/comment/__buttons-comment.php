<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.01.2019
 * Time: 13:29
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $comment array */
/* @var $modelUserForm \common\models\extend\UserExtend */
$modelUserForm = Yii::$app->user->identity;

//d($comment);
?>
<?php if ($comment['created_by'] != Yii::$app->user->id): ?>
    <?php if ($modelUserForm->document_id): ?>
        <?= Html::a(Yii::t('app', 'Ответить'), 'javascript:void(0)',
            [
                'onclick' => '
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['/comment/create-comment', 'item_id' => $comment['item_id'], 'comment_id' => $comment['id']]) . '",
                        container: "#block-comment-update-form-' . $comment['id'] . '",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })'
            ]) ?>
    <?php else: ?>
        <?php
        $url = Url::to(['/profile/default/create-profile',
            'url' => Url::to(['/comment/refresh-comment', 'item_id' => $comment['item_id']]),
            'container' => '#comment-widget',
        ]);
        ?>
        <?= Html::a(Yii::t('app', '<span class="text-danger">Ответить <i class="fas fa-user-times"></i></span>'), 'javascript:void(0)',
            [
                'onclick' => '
                    $.pjax({
                        type: "POST", 
                        url: "'.$url.'",
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    });'
            ]) ?>
    <?php endif; ?>
<?php endif; ?>
<?php if ($comment['created_by'] == Yii::$app->user->id): ?>
    <?php
    //d($comment);
    ?>
    <?= Html::a(Yii::t('app', 'Редактировать'), 'javascript:void(0)',
        [
            'onclick' => '
                $.pjax({
                    type: "GET",
                    url: "' . Url::to(['/comment/update-comment', 'id' => $comment['id']]) . '",
                    container: "#block-comment-update-form-' . $comment['id'] . '",
                    push: false,
                    timeout: 10000,
                    scrollTo: false
                })'
        ]) ?>
    <?= Html::a(Yii::t('app', 'Удалить'), 'javascript:void(0)',
        [
            'onclick' => '
                $.pjax({
                    type: "GET",
                    url: "' . Url::to(['/comment/confirm-delete-comment', 'id' => $comment['id']]) . '",
                    container: "#pjaxModalUniversal",
                    push: false,
                    timeout: 10000,
                    scrollTo: false
                })',
        ]) ?>
<?php endif; ?>

