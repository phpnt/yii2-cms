<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 25.10.2018
 * Time: 12:25
 */

use yii\helpers\Html;
use yii\helpers\Url;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $modelValueFileForm \common\models\forms\ValueFileForm */
?>
<div id="element-file">
    <?php BootstrapNotify::widget([]) ?>
    <?php if (isset($modelValueFileForm)): ?>
        <?= Html::a('<span class="fa fa-trash"></span>', 'javascript:void(0);', [
            'class' => 'text-danger',
            'title' => Yii::t('app', 'Удалить файл?'),
            'onclick' => '
                if (confirm("'. Yii::t('app', 'Удалить элемент?') .'")) {
                    $.pjax({
                        type: "GET",
                        url: "' . Url::to(['delete-file', 'id' => $modelValueFileForm->id]) . '",
                        container: "#element-file",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    });
                }'
        ]) ?>
        <?= $modelValueFileForm->name ?>
    <?php endif; ?>
</div>
