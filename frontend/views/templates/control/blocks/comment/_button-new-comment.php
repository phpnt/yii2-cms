<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.01.2019
 * Time: 11:22
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $document_id int */
/* @var $access_answers boolean */
/* @var $modelUserForm \common\models\extend\UserExtend */
$modelUserForm = Yii::$app->user->identity;
?>
<?php if ($modelUserForm->document_id): ?>
    <?= Html::button(Yii::t('app', 'Добавить комментарий'),
        [
            'class' => 'btn btn-primary',
            'onclick' => '
                $.pjax({
                    type: "GET",
                    url: "' . Url::to(['/comment/create-comment', 'document_id' => $document_id, 'access_answers' => $access_answers]) . '",
                    container: "#block-comment-add-form-' . $document_id . '",
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
    <?= Html::button(Yii::t('app', 'Добавить комментарий <i class="fas fa-user-times"></i>'),
        [
            'class' => 'btn btn-danger',
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

