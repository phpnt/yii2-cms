<?php

namespace frontend\modules\login\controllers;

use common\models\forms\LoginForm;
use common\models\forms\PasswordResetRequestForm;
use common\models\forms\ResetPasswordForm;
use common\models\forms\VisitForm;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Default controller for the `login` module
 */
class DefaultController extends Controller
{
    // информация о текущей странице
    public $page;

    public function init()
    {
        parent::init();
        $this->page = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => $this->module->id])
            ->one();
    }

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
                        'allow' => true,
                        'roles' => Yii::$app->userAccess->getUserAccess($this->page['access'])
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws ErrorException
     */
    public function beforeAction($action)
    {
        try {
            parent::beforeAction($action);
        } catch (BadRequestHttpException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new ErrorException($e->getMessage());
        }

        if ($alias = Yii::$app->request->get('alias')) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'alias' => $alias,
                    'parent_id' => $this->page['id'],
                ])
                ->one();
            $document_id = $data['id'];
        } else {
            $document_id = $this->page['id'];
        }

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            // с одним IP обновляется раз в сутки
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('visit')
                ->where([
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->andWhere(['>', 'created_at', time() - 24*60*60])
                ->one();
            if ($data == false) {
                $modelVisitForm = new VisitForm();
                $modelVisitForm->created_at = time();
                $modelVisitForm->document_id = $document_id;
                $modelVisitForm->ip = Yii::$app->request->userIP;
                $modelVisitForm->user_agent = Yii::$app->request->userAgent;
                $modelVisitForm->save();
            }
        } else {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('visit')
                ->where([
                    'document_id' => $document_id,
                    'user_id' => Yii::$app->user->id
                ])
                ->one();
            if (!$data) {
                $modelVisitForm = new VisitForm();
                $modelVisitForm->created_at = time();
                $modelVisitForm->document_id = $document_id;
                $modelVisitForm->ip = Yii::$app->request->userIP;
                $modelVisitForm->user_agent = Yii::$app->request->userAgent;
                $modelVisitForm->user_id = Yii::$app->user->id;
                $modelVisitForm->save();
            }
        }

        return true;
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        // Уже авторизированных отправляем на домашнюю страницу
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelLoginForm = new LoginForm();
        if ($modelLoginForm->load(Yii::$app->request->post()) && $modelLoginForm->login()) {
            return $this->goHome();
        }

        return $this->render('index', [
            'page' => $this->page,
            'modelLoginForm' => $modelLoginForm
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
        return $this->render('requestPasswordResetToken', [
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
        } catch (InvalidArgumentException $e) {
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
}
