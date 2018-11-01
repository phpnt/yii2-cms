<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 25.08.2018
 * Time: 12:41
 */

use phpnt\adminLTE\AdminLteAsset;
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
AdminLteAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition login-page">

    <?php $this->beginBody() ?>

    <div class="login-box">
        <div class="login-logo">
            <a href="/"><?= Html::encode($this->title) ?></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <?= BootstrapNotify::widget() ?>
            <?= $content ?>
        </div>
        <!-- /.login-box-body -->
    </div>
    <?php Pjax::begin(['id' => 'pjaxModalUniversal']); ?><?php Pjax::end(); ?>
    <?php Pjax::begin(['id' => 'pjaxModalUniversal2']); ?><?php Pjax::end(); ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>