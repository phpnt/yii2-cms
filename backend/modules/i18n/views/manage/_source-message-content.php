<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 28.10.2018
 * Time: 22:20
 */

use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $modelSourceMessageForm \common\models\forms\SourceMessageForm  */

$locations = isset($modelSourceMessageForm->location) ? Json::decode($modelSourceMessageForm->location) : [];
?>
    <div class="source-message-content">
        <strong style="color: #006e00;"><?= Html::encode($modelSourceMessageForm->message) ?></strong>
    </div>
<?php
if (is_array($locations) && !empty($locations) ) {
    echo Html::ul(array_unique($locations), [
        'class' => 'trace',
        'item' => function ($location) {
            return "<li>{$location}</li>";
        },
    ]);
}