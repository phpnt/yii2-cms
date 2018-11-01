<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 21:47
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $document_id int */
/* @var $likes int */
?>
<div id="like-widget">
    <?= Html::a(Yii::t('app', 'Нравиться {likes}', ['likes' => $likes]), 'javascript:void(0);', [
        'class' => 'btn btn-default',
        'onclick' => '
        $.pjax({
            type: "GET",
            url: "' . Url::to(['/like/update', 'document_id' => $document_id]) . '",
            container: "#like-widget",
            push: false,
            timeout: 10000,
            scrollTo: false
        })'
    ]); ?>
</div>
