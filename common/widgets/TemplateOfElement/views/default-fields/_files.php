<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 25.10.2018
 * Time: 14:12
 */

use yii\helpers\Html;
use yii\helpers\Url;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $manyValueFileForm array */
/* @var $modelValueFileForm \common\models\forms\ValueFileForm */
?>
<div id="element-files">
    <?php BootstrapNotify::widget([]) ?>
    <?php if (isset($manyValueFileForm)): ?>
        <?php foreach ($manyValueFileForm as $modelValueFileForm): ?>
        <?= Html::a('<span class="fa fa-trash"></span>', 'javascript:void(0);', [
            'class' => 'text-danger',
            'title' => Yii::t('app', 'Удалить файл?'),
            'onclick' => '
                if (confirm("'. Yii::t('app', 'Удалить элемент?') .'")) {
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['delete-file', 'id' => $modelValueFileForm->id]) . '",
                        container: "#element-files",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    });
                }'
        ]) ?>
        <?= $modelValueFileForm->name ?><br>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
