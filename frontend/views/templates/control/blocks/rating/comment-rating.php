<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 06.02.2019
 * Time: 9:45
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $comment_id int */
/* @var $likes int */
/* @var $dislikes int */
?>
<span id="comment-rating-widget-<?= $comment_id ?>"  class="block-comment-rating">
    <?= Html::a('<i class="fas fa-thumbs-up"></i> ' . $likes, 'javascript:void(0);', [
        'title' => Yii::t('app', 'Нравиться'),
        'class' => 'btn btn-xs',
        'onclick' => '
            $.pjax({
                type: "GET",
                url: "' . Url::to(['/rating/comment-like', 'comment_id' => $comment_id]) . '",
                container: "#comment-rating-widget-' . $comment_id . '",
                push: false,
                timeout: 10000,
                scrollTo: false
            })'
    ]); ?>
    <?= Html::a('<i class="fas fa-thumbs-down"></i> ' . $dislikes, 'javascript:void(0);', [
        'class' => 'btn btn-xs',
        'title' => Yii::t('app', 'Не нравиться'),
        'onclick' => '
            $.pjax({
                type: "GET",
                url: "' . Url::to(['/rating/comment-dislike', 'comment_id' => $comment_id]) . '",
                container: "#comment-rating-widget-' . $comment_id . '",
                push: false,
                timeout: 10000,
                scrollTo: false
            })'
    ]); ?>
</span>