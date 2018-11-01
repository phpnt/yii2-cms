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
                        <?php if ($modelUserForm->image): ?>
                            <img src="/<?=$modelUserForm->image ?>" class="user-image"/>
                        <?php else: ?>
                            <span class="glyphicon glyphicon-user"></span>
                        <?php endif; ?>
                        <span class="hidden-xs"><?=$modelUserForm->first_name?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <?php if ($modelUserForm->image) { ?>
                                <img src="/<?=$modelUserForm->image ?>" class="img-circle"/>
                            <?php } else {
                                if ($modelUserForm->sex === \common\models\Constants::SEX_FEMALE) {
                                    echo "<img src='".$userAsset->baseUrl ."/image/female.png' class='img-circle'>";
                                } else {
                                    echo "<img src='".$userAsset->baseUrl ."/image/male.png' class='img-circle'>";
                                }
                            }?>
                            <p>
                                <?=$modelUserForm->first_name . " " . $modelUserForm->last_name?>
                                <small><?=$modelUserForm->email?></small>
                            </p>
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
