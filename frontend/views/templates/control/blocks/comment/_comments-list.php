<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.01.2019
 * Time: 13:00
 */

use common\widgets\Rating\CommentRating;

/* @var $this yii\web\View */
/* @var $item_id int */
/* @var $comments array */
/* @var $access_answers boolean */
/* @var $commentsManage \common\widgets\Comment\components\CommentManage */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
/* @var $modelUserForm \common\models\extend\UserExtend */
$fieldsManage = Yii::$app->fieldsManage;
$modelUserForm = Yii::$app->user->identity;
$commentsManage = Yii::$app->commentsManage;
?>
<div class="comment-list">
    <?php foreach ($comments as $comment): ?>
        <strong><?= $fieldsManage->getUserValueByName($name = 'Имя', $comment['created_by']) ?></strong>:
        <div class="comment-item">
            <?= $comment['content'] ?>
            <p><?= Yii::$app->formatter->asDate($comment['updated_at']) ?></p>
        </div>
        <div id="block-comment-update-form-<?= $comment['id'] ?>" class="m-b-md text-right">
            <?php if (!Yii::$app->user->isGuest): ?>
                <?= $this->render('__buttons-comment', [
                    'comment' => $comment,
                ]) ?>
            <?php endif; ?>
            &nbsp;&nbsp;&nbsp;<?= CommentRating::widget(['comment_id' => $comment['id']]) ?>
        </div>
        <?php $commentsIn = $commentsManage->getChilds($comment['id']); ?>
        <?php if ($commentsIn): ?>
            <div class="comment-sub-item">
                <?= $this->render('_comments-list', [
                    'item_id' => $item_id,
                    'comments' => $commentsIn
                ]) ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
