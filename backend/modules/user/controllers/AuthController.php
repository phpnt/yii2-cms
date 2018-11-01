<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

namespace backend\modules\user\controllers;

use common\models\forms\PasswordResetRequestForm;
use common\models\forms\ResetPasswordForm;
use Yii;
use common\models\forms\LoginForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class AuthController extends Controller
{
    public $layout = 'main-login';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'request-password-reset', 'reset-password', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Авторизация
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        // Уже авторизированных отправляем на домашнюю страницу
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelLoginForm = new LoginForm();
        if ($modelLoginForm->load(Yii::$app->request->post()) && $modelLoginForm->login()) {
            return $this->goHome();
        }

        return $this->render('login', [
            'modelLoginForm' => $modelLoginForm,
        ]);
    }

    /**
     * Запрос на сброс пароля.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $modelPasswordResetRequestForm = new PasswordResetRequestForm();
        if ($modelPasswordResetRequestForm->load(Yii::$app->request->post()) && $modelPasswordResetRequestForm->validate()) {
            if ($modelPasswordResetRequestForm->sendEmail()) {
                Yii::$app->session->set(
                    'message',
                    [
                        'type'      => 'success',
                        'icon'      => 'glyphicon glyphicon-ok',
                        'message'   => Yii::t('app', 'Проверьте ваш Емайл и следуйте дальнейшим инструкциям.'),
                    ]
                );
                return $this->goHome();
            } else {
                Yii::$app->session->set(
                    'message',
                    [
                        'type'      => 'danger',
                        'icon'      => 'glyphicon glyphicon-ban',
                        'message'   => Yii::t('app', 'Сожалеем, мы не смогли сбросить пароль для указанного адреса электронной почты'),
                    ]
                );
            }
        }

        if ($modelPasswordResetRequestForm->errors) {
            return $this->renderAjax('_requestPasswordResetToken-form', [
                'modelPasswordResetRequestForm' => $modelPasswordResetRequestForm,
            ]);
        }

        return $this->renderAjax('requestPasswordResetToken', [
            'modelPasswordResetRequestForm' => $modelPasswordResetRequestForm,
        ]);
    }

    /**
     * Сброс пароля
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $modelResetPasswordForm = new ResetPasswordForm($token);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($modelResetPasswordForm->load(Yii::$app->request->post()) && $modelResetPasswordForm->validate() && $modelResetPasswordForm->resetPassword()) {
            Yii::$app->session->set(
                'message',
                [
                    'type'      => 'success',
                    'icon'      => 'glyphicon glyphicon-ok',
                    'message'   => Yii::t('app', 'Новый пароль сохранен.'),
                ]
            );
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'modelResetPasswordForm' => $modelResetPasswordForm,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}