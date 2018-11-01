<?php

namespace backend\modules\user\controllers;

use common\models\forms\UserForm;
use common\models\search\UserSearch;
use Yii;
use yii\base\ErrorException;
use yii\base\InlineAction;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `user` module
 */
class ManageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            /* @var $action InlineAction */
                            if (!Yii::$app->user->can($action->controller->module->id . '/' . $action->controller->id . '/' . $action->id)) {
                                throw new ForbiddenHttpException(Yii::t('app', 'У вас нет доступа к этой странице'));
                            };
                            return true;
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Управление пользователями
     * @return string
     */
    public function actionIndex()
    {
        $allUserSearch = new UserSearch();
        $dataProviderUserSearch = $allUserSearch->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'allUserSearch' => $allUserSearch,
            'dataProviderUserSearch' => $dataProviderUserSearch,
        ]);
    }

    /**
     * Обновление пользователей
     * @return string
     */
    public function actionRefreshUser()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $allUserSearch = new UserSearch();
        $dataProviderUserSearch = $allUserSearch->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_grid-user-block', [
            'allUserSearch' => $allUserSearch,
            'dataProviderUserSearch' => $dataProviderUserSearch,
        ]);
    }

    /**
     * Создание пользователей
     * @return string
     */
    public function actionCreateUser()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelUserForm = new UserForm();
        $modelUserForm->scenario = 'create-user';

        if ($modelUserForm->load(Yii::$app->request->post()) && $modelUserForm->save()) {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Успешно'),
                ]
            );
            return $this->asJson(['success' => 1]);
        }

        if ($modelUserForm->errors) {
            return $this->renderAjax('_form-user', [
                'modelUserForm' => $modelUserForm,
            ]);
        }

        return $this->renderAjax('modal-user', [
            'modelUserForm' => $modelUserForm,
        ]);
    }

    /**
     * Изменение пользователей
     * @return string
     */
    public function actionUpdateUser($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelUserForm = UserForm::findOne($id);

        if ($modelUserForm->load(Yii::$app->request->post()) && $modelUserForm->save()) {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Успешно'),
                ]
            );
            return $this->asJson(['success' => 1]);
        }

        if ($modelUserForm->errors) {
            return $this->renderAjax('_form-user', [
                'modelUserForm' => $modelUserForm,
            ]);
        }

        return $this->renderAjax('modal-user', [
            'modelUserForm' => $modelUserForm,
        ]);
    }

    /**
     * Просмотр пользователя
     * @return string
     */
    public function actionViewUser($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelUserForm = UserForm::findOne($id);

        return $this->renderAjax('view-user', [
            'modelUserForm' => $modelUserForm,
        ]);
    }

    /**
     * Подтверждение удаления пользователя
     * @return string
     */
    public function actionConfirmDeleteUser($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('confirm-delete-user', [
            'id' => $id,
        ]);
    }

    /**
     * Удаление пользователя
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteUser($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelUserForm = UserForm::findOne($id);

        try {
            $modelUserForm->delete();
        } catch (StaleObjectException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new ErrorException($e->getMessage());
        } catch (\Throwable $e) {
            Yii::$app->errorHandler->logException($e);
            throw new ErrorException($e->getMessage());
        }

        Yii::$app->session->set(
            'message',
            [
                'type' => 'success',
                'icon' => 'glyphicon glyphicon-ok',
                'message' => Yii::t('app', 'Успешно'),
            ]
        );

        $allUserSearch = new UserSearch();
        $dataProviderUserSearch = $allUserSearch->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_grid-user-block', [
            'allUserSearch' => $allUserSearch,
            'dataProviderUserSearch' => $dataProviderUserSearch,
        ]);
    }
}
