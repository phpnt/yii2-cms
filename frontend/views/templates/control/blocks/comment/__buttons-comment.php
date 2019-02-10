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
/* @var $document_id int */
/* @var $comment_id int */
/* @var $user_id int */
/* @var $access_answers boolean */
/* @var $modelUserForm \common\models\extend\UserExtend */
$modelUserForm = Yii::$app->user->identity;
?>
<?php if ($access_answers && $user_id != Yii::$app->user->id): ?>
    <?php if ($modelUserForm->document_id): ?>
        <?= Html::a(Yii::t('app', 'Ответить'), 'javascript:void(0)',
            [
                'onclick' => '
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['/comment/create-comment', 'document_id' => $document_id, 'comment_id' => $comment_id, 'access_answers' => $access_answers]) . '",
                        container: "#block-comment-update-form-' . $comment_id . '",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })'
            ]) ?>
    <?php else: ?>
        <?php
        $url = Url::to(['/profile/default/create-profile',
            'url' => Url::to(['/comment/refresh-comment', 'document_id' => $document_id, 'access_answers' => $access_answers]),
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
<?php if ($user_id == Yii::$app->user->id): ?>
    <?= Html::a(Yii::t('app', 'Редактировать'), 'javascript:void(0)',
        [
            'onclick' => '
                $.pjax({
                    type: "GET",
                    url: "' . Url::to(['/comment/update-comment', 'document_id' => $document_id, 'comment_id' => $comment_id, 'access_answers' => $access_answers]) . '",
                    container: "#block-comment-update-form-' . $comment_id . '",
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
                    url: "' . Url::to(['/comment/confirm-delete-comment', 'document_id' => $document_id, 'comment_id' => $comment_id, 'access_answers' => $access_answers]) . '",
                    container: "#pjaxModalUniversal",
                    push: false,
                    timeout: 10000,
                    scrollTo: false
                })',
        ]) ?>
<?php endif; ?>

