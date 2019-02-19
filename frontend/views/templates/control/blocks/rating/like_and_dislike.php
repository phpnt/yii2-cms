<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 18.01.2019
 * Time: 17:45
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $document_id int */
/* @var $likes int */
/* @var $dislikes int */
?>
<div class="block-rating">
    <?= Html::a('<i class="fas fa-thumbs-up"></i> ' . $likes, 'javascript:void(0);', [
        'class' => 'btn btn-xs btn-success',
        'onclick' => '
            $.pjax({
                type: "GET",
                url: "' . Url::to(['/rating/like', 'document_id' => $document_id, 'dislike' => true]) . '",
                container: "#rating-widget-' . $document_id .'",
                push: false,
                timeout: 10000,
                scrollTo: false
            })'
    ]); ?>
    <?= Html::a('<i class="fas fa-thumbs-down"></i> ' . $dislikes, 'javascript:void(0);', [
        'class' => 'btn btn-xs btn-danger',
        'onclick' => '
            $.pjax({
                type: "GET",
                url: "' . Url::to(['/rating/dislike', 'document_id' => $document_id, 'like' => true]) . '",
                container: "#rating-widget-' . $document_id .'",
                push: false,
                timeout: 10000,
                scrollTo: false
            })'
    ]); ?>
</div>