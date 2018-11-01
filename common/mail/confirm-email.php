<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:29
 */

/* @var $this yii\web\View */
/* @var $modelUserForm \common\models\forms\UserForm */

use yii\helpers\Html;

if (isset($modelUserForm) && $modelUserForm):
    $confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['/signup/default/confirm', 'token' => $modelUserForm->email_confirm_token]);
?>

<p><?=Yii::t('app', 'Здравствуйте')?>, <?= Html::encode($modelUserForm->first_name) ?>!</p>

<p><?=Yii::t('app', 'Для подтверждения адреса и первичной авторизации пройдите по ссылке')?>:

<?= Html::a(Html::encode($confirmLink), $confirmLink) ?>.</p>

<p><?=Yii::t('app', 'Если Вы не регистрировались на нашем сайте, то просто удалите это письмо.')?></p>

<?php
endif;
?>
