<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.01.2019
 * Time: 9:49
 */

use phpnt\bootstrapNotify\BootstrapNotify;
use frontend\views\templates\control\blocks\comment\assets\CommentTempAsset;

/* @var $this yii\web\View */
/* @var $item_id int */
/* @var $comments array */
/* @var $access_answers boolean */

CommentTempAsset::register($this);
?>
<div class="block-comment m-t-lg">
    <?php BootstrapNotify::widget() ?>
    <div class="m-b-md">
        <h3><?= Yii::t('app', 'Комментарии:') ?></h3>
    </div>
    <div class="m-b-md">
        <div class="slim-scroll-comments block-comments-height" data-height="600px">
            <?= $this->render('_comments-list', [
                'item_id' => $item_id,
                'comments' => $comments,
            ]) ?>
        </div>
    </div>
    <?php if (!Yii::$app->user->isGuest): ?>
        <div id="block-comment-add-form-<?= $item_id ?>">
            <?= $this->render('_button-new-comment', [
                'item_id' => $item_id,
            ]) ?>
        </div>
    <?php else: ?>
        <div class="m-t-md">
            <p><?= Yii::t('app', 'Комментарии могут оставлять только зарегистрированные пользователи.') ?></p>
        </div>
    <?php endif; ?>
</div>
<div class="clearfix m-b-lg"></div>
