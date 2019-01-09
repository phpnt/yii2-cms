<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use yii\bootstrap\Html;
use yii\helpers\Url;
use common\widgets\LangSwitch\LangSwitch;

/* @var $this \yii\web\View */
/* @var $modelUserForm \common\models\forms\UserForm */
/* @var $userAsset \backend\modules\user\assets\UserAsset */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">CMS</span><span class="logo-lg">' . Yii::$app->name . '</span>', ['/'], ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"></span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-user"></span>
                        <span class="hidden-xs"><?= $modelUserForm->email ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="<?= $userAsset->baseUrl . '/image/male.png' ?>" class='img-circle'>
                            <p><?= $modelUserForm->email ?></p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    Yii::t('app', 'Профиль'),
                                    'javascript:void(0);',
                                    ['class' => 'btn btn-default btn-flat disabled']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Выйти',
                                    ['/user/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <li>
                    <?= Html::a('<i class="fa fa-sitemap"></i> ' . Yii::t('app', 'Папки и элементы'), Url::to(['javascript:void(0);']), [
                        'data-toggle' => 'control-sidebar'
                    ]) ?>
                </li>
                <?= LangSwitch::widget() ?>
            </ul>
        </div>
    </nav>
</header>
