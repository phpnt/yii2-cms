<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 18.01.2019
 * Time: 17:38
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $document_id int */
/* @var $dislikes int */
?>
<div id="dislike-widget-<?= $document_id ?>">
    <?= Html::a('<i class="fas fa-thumbs-down"></i> ' . $dislikes, 'javascript:void(0);', [
        'class' => 'btn btn-danger',
        'onclick' => '
            $.pjax({
                type: "GET",
                url: "' . Url::to(['/rating/dislike', 'document_id' => $document_id, 'like' => false]) . '",
                container: "#dislike-widget-' . $document_id . '",
                push: false,
                timeout: 10000,
                scrollTo: false
            })'
    ]); ?>
</div>