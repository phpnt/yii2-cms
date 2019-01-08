<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\widgets\oAuth\controllers;

use common\models\Constants;
use common\models\forms\UserForm;
use common\models\forms\UserOauthKeyForm;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\OAuth2;
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
    public $modelUser;
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
        /** @var $modelUserForm UserForm */
        $modelUserForm = $this->modelUser;

        /* @var $client OAuth2 */
        $attributes = $client->userAttributes;

        $this->action->successUrl = Yii::$app->session->get('returnUrl');

        /**
         * Проверяем, есть ли запись данного пользователя с данной соц сетью в таблице
         * @var $model UserOauthKeyForm
         */

        $modelUserOauthKeyForm = UserOauthKeyForm::findOne([
            'provider_id' => $attributes['provider_id'],
            'provider_user_id' => $attributes['provider_user_id']
        ]);

        if ($modelUserOauthKeyForm) {
            // Ключ авторизации соц. сети найден в базе
            if ($modelUserOauthKeyForm->user->status == Constants::STATUS_ACTIVE) {
                // Авторзириуемся если Гость
                //$modelUserForm = $modelUserOauthKeyForm->getUser($modelUserForm);
                return Yii::$app->user->login($modelUserOauthKeyForm->user, 3600 * 24 * 30);
            } else {
                // Найден ключ и статус заблокированный
                Yii::$app->session->set(
                    'message',
                    [
                        'type'      => 'danger',
                        'icon'      => 'glyphicon glyphicon-envelope',
                        'message'   => ' '.Yii::t('app', 'Пользователь {email} не авторизован или заблокирован. Если не авторизован, проверьте эл. почту или воспользуйтесь процедурой восстановления пароля.', ['email' => $modelUserOauthKeyForm->user->email]),
                    ]
                );
                return false;
            }
        } else {
            // Текущего ключа авторизации соц. сети нет в базе
            if (Yii::$app->user->isGuest) {
                if ($attributes['User']['email'] != null) {
                    // Пытаемся найти пользователя в базе по почте из соц. сети
                    $modelUserForm = $modelUserForm::findByEmail($attributes['User']['email']);
                    if ($modelUserForm && $modelUserForm->status == Constants::STATUS_ACTIVE) {
                        // Найден Email и статус активный. Добавляем ключ и авторизируемся
                        $status = ($this->createKey($attributes, $modelUserForm->id) && Yii::$app->user->login($modelUserForm, 3600 * 24 * 30));
                        if ($status) {
                            Yii::$app->session->set(
                                'message',
                                [
                                    'type'      => 'success',
                                    'icon'      => 'glyphicon glyphicon-envelope',
                                    'message'   => ' '.Yii::t('app', 'Авторизация прошла успешно'),
                                ]
                            );
                            return true;
                        } else {
                            dd('Не записаны ключи');
                        }
                    } elseif ($modelUserForm && $modelUserForm->status == Constants::STATUS_WAIT) {
                        // Найден Email и статус не активный
                        $status = ($this->createKey($attributes, $modelUserForm->id) && $modelUserForm->confirmEmail());
                        if ($status) {
                            Yii::$app->session->set(
                                'message',
                                [
                                    'type'      => 'success',
                                    'icon'      => 'glyphicon glyphicon-envelope',
                                    'message'   => ' '.Yii::t('app', 'Ссылка с подтверждением регистрации отправлена на {email}.', ['email' => $modelUserForm->email]),
                                ]
                            );
                        }
                        return true;
                    } elseif ($modelUserForm && $modelUserForm->status == Constants::STATUS_BLOCKED) {
                        // Найден Email и статус заблокированный
                        Yii::$app->session->set(
                            'message',
                            [
                                'type'      => 'danger',
                                'icon'      => 'glyphicon glyphicon-envelope',
                                'message'   => ' '.Yii::t('app', 'Пользователь {email} заблокирован.', ['email' => $modelUserForm->email]),
                            ]
                        );
                        return false;
                    }
                }

                $modelUserForm = new UserForm();
                $modelUserForm->email = $attributes['User']['email'];
                $modelUserForm->first_name = $attributes['User']['first_name'];
                $modelUserForm->last_name = $attributes['User']['last_name'];
                $modelUserForm->sex = $attributes['User']['gender'];
                $modelUserForm->status = isset($attributes['User']['status']) ? $attributes['User']['status'] : Constants::STATUS_WAIT;
                $modelUserForm->provider_id = $attributes['provider_id'];
                $modelUserForm->provider_user_id = $attributes['provider_user_id'];
                $modelUserForm->page = $attributes['page'];
                echo $this->render('@frontend/views/site/signup-auth', ['modelUserForm' => $modelUserForm]);
                Yii::$app->end();
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
     * @return mixed
     */
    public function actionSignup()
    {
        $modelUserForm = new UserForm();

        if ($modelUserForm->load(Yii::$app->request->post()) && $modelUserForm->save()) {
            if ($modelUserForm->status == Constants::STATUS_ACTIVE) {
                Yii::$app->session->set(
                    'message',
                    [
                        'type'      => 'success',
                        'icon'      => 'glyphicon glyphicon-envelope',
                        'message'   => ' '.Yii::t('app', 'Логин и пароль отправлены на {email}.', ['email' => $modelUserForm->email]),
                    ]
                );
                Yii::$app->user->login($modelUserForm, 3600 * 24 * 30);
            } else {
                Yii::$app->session->set(
                    'message',
                    [
                        'type'      => 'success',
                        'icon'      => 'glyphicon glyphicon-envelope',
                        'message'   => ' '.Yii::t('app', 'Ссылка с подтверждением регистрации отправлена на {email}.', ['email' => $modelUserForm->email]),
                    ]
                );
            }

            return $this->redirect('close');
        }

        if (!Yii::$app->request->isPjax || !Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        if ($modelUserForm->errors) {
            return $this->renderAjax('@frontend/views/site/_signup-auth-form', [
                'modelUserForm' => $modelUserForm,
            ]);
        }

        return $this->renderAjax('@frontend/views/site/signup-auth', [
            'modelUserForm' => $modelUserForm,
        ]);
    }


    /**
     * @return mixed
     */
    public function actionChangeCountry()
    {
        // Уже авторизированных отправляем на домашнюю страницу
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelUserForm = new UserForm();

        $modelUserForm->load(Yii::$app->request->post());

        return $this->renderAjax('@frontend/views/site/_signup-auth-form', [
            'modelUserForm' => $modelUserForm,
        ]);
    }

    /**
     * Закрытие модального окна
     * @return mixed
     */
    public function actionClose() {
        $viewFile = '@frontend/views/site/redirect.php';

        if ($viewFile === null) {
            $viewFile = __DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'redirect.php';
        } else {
            $viewFile = Yii::getAlias($viewFile);
        }
        $viewData = [
            'url' => 'http://itwillok.com/',
            'enforceRedirect' => true,
        ];
        $response = Yii::$app->getResponse();
        $response->content = Yii::$app->getView()->renderFile($viewFile, $viewData);
        return $response;
    }


    /**
     * Результат успешной авторизации с помощью социальной сети
     * @param $client - социальная сеть, через которую происходит авторизация
     * @return bool
     */
    public function successCallbackOld($client)
    {
        $modelUser = $this->modelUser;
        /* @var $client OAuth2 */
        $attributes = $client->userAttributes;

        dd($attributes);

        $this->action->successUrl = Yii::$app->session->get('returnUrl');

        /** @var UserOauthKey $model */
        $model = UserOauthKey::findOne([
            'provider_id' => $attributes['provider_id'],
            'provider_user_id' => $attributes['provider_user_id']
        ]);

        if ($model) {
            // Ключ авторизации соц. сети найден в базе
            if (Yii::$app->user->isGuest) {
                // Авторзириуемся если Гость
                $user = $model->getUser($modelUser);
                return Yii::$app->user->login($model->getUser($user), 3600 * 24 * 30);
            } else {
                // Запрщаем авторизацию если не свой ключ
                if ($model->user_id != Yii::$app->user->id) {
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
                if ($attributes['User']['email'] != null) {
                    // Пытаемся найти пользователя в базе по почте из соц. сети
                    $user = $modelUser::findByEmail($attributes['User']['email']);
                }
                if (!$user) {
                    // Не найден пользователь с Email, создаем нового
                    $user = new $modelUser ();
                    $user->load($attributes);
                    if ($user->save() && $this->createKey($attributes, $user->id)) {
                        Yii::$app->session->set(
                            'message',
                            [
                                'type'      => 'success',
                                'icon'      => 'glyphicon glyphicon-ok',
                                'message'   => Yii::t('app', 'Авторизация прошла успешно'),
                            ]
                        );
                    }
                    return (Yii::$app->user->login($user, 3600 * 24 * 30));
                } else {
                    // Найден Email. Добавляем ключ и авторизируемся
                    return ($this->createKey($attributes, $user->id) && Yii::$app->user->login($user, 3600 * 24 * 30));
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

    /**
     * Удлаение ключа авторизации соц. сети (отвзяывание)
     * @param $id - ID ключа авторизации
     * @return \yii\web\Response
     */
    public function actionUnbind($id)
    {
        $modelUser = $this->modelUser;
        /** @var UserOauthKey $model */
        $model = UserOauthKey::findOne(['user_id' => Yii::$app->user->id, 'provider_id' => UserOauthKey::getAvailableClients()[$id]]);
        if (!$model) {
            Yii::$app->session->set(
                'message',
                [
                    'type'      => 'danger',
                    'icon'      => 'glyphicon glyphicon-warning-sign',
                    'message'   => Yii::t('app', 'Ключ не найден'),
                ]
            );
        } else {
            /** @var User $user */
            $user = $modelUser::findOne($model->user_id);
            if ($user) {
                if (UserOauthKey::isOAuth($user->id) <= 1 && $user->email === null) {
                    Yii::$app->session->set(
                        'message',
                        [
                            'type'      => 'danger',
                            'icon'      => 'glyphicon glyphicon-warning-sign',
                            'message'   => Yii::t('app', 'Нельзя отвязать единственную соц. сеть, не заполнив Email'),
                        ]
                    );
                } elseif (UserOauthKey::isOAuth($user->id)<=1 && $user->password_hash === null) {
                    Yii::$app->session->set(
                        'message',
                        [
                            'type'      => 'danger',
                            'icon'      => 'glyphicon glyphicon-warning-sign',
                            'message'   => Yii::t('app', 'Нельзя отвязать единственную соц. сеть, не заполнив пароль'),
                        ]
                    );
                } else {
                    $model->delete();
                    Yii::$app->session->set(
                        'message',
                        [
                            'type'      => 'danger',
                            'icon'      => 'glyphicon glyphicon-ok',
                            'message'   => Yii::t('app', 'Ключ входа удален'),
                        ]
                    );
                }
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}
