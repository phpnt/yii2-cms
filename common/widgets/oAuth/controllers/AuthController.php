<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\widgets\oAuth\controllers;

use common\models\Constants;
use common\models\forms\OAuthForm;
use common\models\forms\UserForm;
use common\models\forms\UserOauthKeyForm;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\OAuth2;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Авторизация и регстрация через
 * соц. сети. Прикрепление и открепление
 * ключей авторизации
 * 
 * Class AuthController
 */
class AuthController extends Controller
{
    public $modelOAuthForm;
    public $layout = 'auth';

    /**
     * Сохранение адреса для возврата
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id == 'index' && Yii::$app->request->referrer !== null) {
            Yii::$app->session->set('returnUrl', Yii::$app->request->referrer);
        }
        return parent::beforeAction($action);
    }

    /**
     * Авторизация в социальной сети
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'successCallback']
            ],
        ];
    }

    /**
     * Результат успешной авторизации с помощью социальной сети
     * @param $client - социальная сеть, через которую происходит авторизация
     * @return bool
     */
    public function successCallback($client)
    {
        /* @var $client OAuth2 */
        $attributes = $client->userAttributes;
        $this->action->successUrl = Yii::$app->session->get('returnUrl');

        $userOauthKey = (new \yii\db\Query())
            ->select(['*'])
            ->from('user_oauth_key')
            ->where([
                'provider_id' => $attributes['provider_id'],
                'provider_user_id' => $attributes['provider_user_id']
            ])
            ->one();

        if ($userOauthKey) {
            // Ключ авторизации соц. сети найден в базе
            if (Yii::$app->user->isGuest) {
                // Авторзириуемся если Гость
                /* @var $modelUserForm UserForm */
                $modelUserForm = UserForm::findOne($userOauthKey['user_id']);
                if ($modelUserForm->status == Constants::STATUS_WAIT) {
                    Yii::$app->session->set(
                        'message',
                        [
                            'type'      => 'success',
                            'icon'      => 'glyphicon glyphicon-ok',
                            'message'   => Yii::t('app', 'Необходимо подтвердить ваш емайл.'),
                        ]
                    );
                    return $this->redirect(Url::to(['/signup/default/confirm-email', 'user_id' => $modelUserForm->id]));
                }
                return Yii::$app->user->login($modelUserForm, 3600 * 24 * 30);
            } else {
                // Запрщаем авторизацию если не свой ключ
                if ($userOauthKey['user_id'] != Yii::$app->user->id) {
                    Yii::$app->session->set(
                        'message',
                        [
                            'type'      => 'danger',
                            'icon'      => 'glyphicon glyphicon-warning-sign',
                            'message'   => Yii::t('app', 'Данный ключ уже закреплен за другим пользователем сайта.'),
                        ]
                    );
                    return true;
                }
            }
        } else {
            // Текущего ключа авторизации соц. сети нет в базе
            if (Yii::$app->user->isGuest) {
                $user = false;
                if ($attributes['OAuthForm']['email'] != null) {
                    // Пытаемся найти пользователя в базе по почте из соц. сети
                    $user = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('user')
                        ->where(['email' => $attributes['OAuthForm']['email']])
                        ->one();
                }
                if (!$user) {
                    // Не найден пользователь с Email, создаем нового
                    $modelOAuthForm = new OAuthForm ();
                    $modelOAuthForm->load($attributes);
                    if ($modelOAuthForm->save() && $this->createKey($attributes, $modelOAuthForm->id)) {
                        Yii::$app->session->set(
                            'message',
                            [
                                'type'      => 'success',
                                'icon'      => 'glyphicon glyphicon-ok',
                                'message'   => Yii::t('app', 'Авторизация прошла успешно.'),
                            ]
                        );
                    }
                    if ($modelOAuthForm->status == Constants::STATUS_ACTIVE) {
                        return (Yii::$app->user->login($modelOAuthForm, 3600 * 24 * 30));
                    }
                    if ($modelOAuthForm->status == Constants::STATUS_WAIT) {
                        Yii::$app->session->set(
                            'message',
                            [
                                'type'      => 'success',
                                'icon'      => 'glyphicon glyphicon-ok',
                                'message'   => Yii::t('app', 'Ключ входа успешно добавлен. Необходимо подтвердить ваш емайл.'),
                            ]
                        );
                        return $this->redirect(Url::to(['/signup/default/confirm-email', 'user_id' => $modelOAuthForm->id]));
                    }
                } else {
                    // Найден Email. Добавляем ключ и авторизируемся
                    $modelOAuthForm = OAuthForm::findOne($user['id']);
                    return ($this->createKey($attributes, $modelOAuthForm->id) && Yii::$app->user->login($modelOAuthForm, 3600 * 24 * 30));
                }
            } else {
                // Добавляем ключ для авторизированного пользователя
                $this->createKey($attributes, Yii::$app->user->id);
                Yii::$app->session->set(
                    'message',
                    [
                        'type'      => 'danger',
                        'icon'      => 'glyphicon glyphicon-ok',
                        'message'   => Yii::t('app', 'Ключ входа успешно добавлен.'),
                    ]
                );
                return true;
            }
        }
        return true;
    }

    /**
     * Закрытие модального окна
     * @return mixed
     */
    public function actionClose() {
        $viewFile = '@frontend/views/templates/oauth/redirect.php';

        if ($viewFile === null) {
            $viewFile = __DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'redirect.php';
        } else {
            $viewFile = Yii::getAlias($viewFile);
        }

        $viewData = [
            'url' => 'http://test.phpnt.com',
            'enforceRedirect' => true,
        ];
        $response = Yii::$app->getResponse();
        $response->content = Yii::$app->getView()->renderFile($viewFile, $viewData);
        return $response;
    }

    /**
     * Создание ключа авторизации соц. сети (привязывание)
     * @param $attributes - аттрибуты пользователя
     * @param $user_id - ID пользователя
     * @return bool
     */
    protected function createKey($attributes, $user_id)
    {
        $modelUserOauthKeyFormForm = new UserOauthKeyForm();
        $modelUserOauthKeyFormForm->provider_id = $attributes['provider_id'];
        $modelUserOauthKeyFormForm->provider_user_id = (string) $attributes['provider_user_id'];
        $modelUserOauthKeyFormForm->page = (string) $attributes['page'];
        $modelUserOauthKeyFormForm->user_id = $user_id;
        return $modelUserOauthKeyFormForm->save();
    }
}
