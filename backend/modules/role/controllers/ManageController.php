<?php

namespace backend\modules\role\controllers;

use common\models\forms\AuthItemChildForm;
use common\models\forms\AuthItemForm;
use common\models\forms\AuthRuleForm;
use common\models\search\AuthItemChildSearch;
use common\models\search\AuthItemSearch;
use common\models\search\AuthRuleSearch;
use Yii;
use yii\base\ErrorException;
use yii\base\InlineAction;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `role` module
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
     * Управление RBAC
     * @return string
     */
    public function actionIndex()
    {
        $allAuthItemSearch = new AuthItemSearch();
        $dataProviderAuthItemSearch = $allAuthItemSearch->search(Yii::$app->request->queryParams);

        $allAuthRuleSearch = new AuthRuleSearch();
        $dataProviderAuthRuleSearch = $allAuthRuleSearch->search(Yii::$app->request->queryParams);

        $allAuthItemChildSearch = new AuthItemChildSearch();
        $dataProviderAuthItemChildSearch = $allAuthItemChildSearch->search(Yii::$app->request->queryParams);

        $modelAuthItemForm = new AuthItemForm();

        return $this->render('index', [
            'allAuthItemSearch' => $allAuthItemSearch,
            'dataProviderAuthItemSearch' => $dataProviderAuthItemSearch,
            'allAuthItemChildSearch' => $allAuthItemChildSearch,
            'dataProviderAuthItemChildSearch' => $dataProviderAuthItemChildSearch,
            'allAuthRuleSearch' => $allAuthRuleSearch,
            'dataProviderAuthRuleSearch' => $dataProviderAuthRuleSearch,
            'modelAuthItemForm' => $modelAuthItemForm
        ]);
    }

    /**
     * Обновление блока ролей или разрешений
     * @return string
     */
    public function actionRefreshAuthItem($name)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $allAuthItemSearch = new AuthItemSearch();
        $dataProviderAuthItemSearch = $allAuthItemSearch->search(Yii::$app->request->queryParams);

        $modelAuthItemForm = AuthItemForm::findOne(['name' => $name]);

        return $this->renderAjax('_grid-auth-item-block', [
            'allAuthItemSearch' => $allAuthItemSearch,
            'dataProviderAuthItemSearch' => $dataProviderAuthItemSearch,
            'modelAuthItemForm' => $modelAuthItemForm
        ]);
    }

    /**
     * Обновление блока ролей или разрешений
     * @return string
     */
    public function actionRefreshAuthItemForm($name = null)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthItemForm = AuthItemForm::findOne(['name' => $name]);

        return $this->renderAjax('_form-auth-item-block', [
            'modelAuthItemForm' => $modelAuthItemForm
        ]);
    }

    /**
     * Создание роль или разрешения
     * @return string
     */
    public function actionCreateAuthItem()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthItemForm = new AuthItemForm();

        if ($modelAuthItemForm->load(Yii::$app->request->post()) && $modelAuthItemForm->save()) {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Успешно'),
                ]
            );
            return $this->asJson([
                'success' => 1,
                'name' => $modelAuthItemForm->name,
                ]);
        }

        if ($modelAuthItemForm->errors) {
            return $this->renderAjax('_form-auth-item', [
                'modelAuthItemForm' => $modelAuthItemForm,
            ]);
        }

        return $this->renderAjax('modal-auth-item', [
            'modelAuthItemForm' => $modelAuthItemForm,
        ]);
    }

    /**
     * Обновление блока ролей или разрешений
     * @return string
     */
    public function actionUpdateAuthItem($name)
    {
        /*if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }*/

        $modelAuthItemForm = AuthItemForm::findOne(['name' => $name]);

        if ($modelAuthItemForm->load(Yii::$app->request->post()) && $modelAuthItemForm->save()) {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Успешно'),
                ]
            );
            return $this->asJson([
                'success' => 1,
                'name' => $modelAuthItemForm->name,
            ]);
        }

        if ($modelAuthItemForm->errors) {
            return $this->renderAjax('_form-auth-item', [
                'modelAuthItemForm' => $modelAuthItemForm,
            ]);
        }

        return $this->renderAjax('modal-auth-item', [
            'modelAuthItemForm' => $modelAuthItemForm,
        ]);
    }

    /**
     * Просмотр роли или разрешения
     * @return string
     */
    public function actionViewAuthItem($name)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthItemForm = AuthItemForm::findOne(['name' => $name]);

        return $this->renderAjax('view-auth-item', [
            'modelAuthItemForm' => $modelAuthItemForm,
        ]);
    }

    /**
     * Подтверждение удаления роли или разрешения
     * @return string
     */
    public function actionConfirmDeleteAuthItem($name)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('confirm-delete-auth-item', [
            'name' => $name,
        ]);
    }

    /**
     * Удаления роли или разрешения
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteAuthItem($name)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthItemForm = AuthItemForm::findOne(['name' => $name]);

        try {
            $modelAuthItemForm->delete();
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

        $allAuthItemSearch = new AuthItemSearch();
        $dataProviderAuthItemSearch = $allAuthItemSearch->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_grid-auth-item-block', [
            'allAuthItemSearch' => $allAuthItemSearch,
            'dataProviderAuthItemSearch' => $dataProviderAuthItemSearch,
            'modelAuthItemForm' => $modelAuthItemForm
        ]);
    }

    /**
     * Обновление блока наследования RBAC
     * @return string
     */
    public function actionRefreshAuthItemChild()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $allAuthItemChildSearch = new AuthItemChildSearch();
        $dataProviderAuthItemChildSearch = $allAuthItemChildSearch->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_grid-auth-item-child-block', [
            'allAuthItemChildSearch' => $allAuthItemChildSearch,
            'dataProviderAuthItemChildSearch' => $dataProviderAuthItemChildSearch,
        ]);
    }

    /**
     * Создание наследования RBAC
     * @return string
     */
    public function actionCreateAuthItemChild()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthItemChildForm = new AuthItemChildForm();

        if ($modelAuthItemChildForm->load(Yii::$app->request->post()) && $modelAuthItemChildForm->save()) {
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

        if ($modelAuthItemChildForm->errors) {
            return $this->renderAjax('_form-auth-item-child', [
                'modelAuthItemChildForm' => $modelAuthItemChildForm,
            ]);
        }

        return $this->renderAjax('modal-auth-item-child', [
            'modelAuthItemChildForm' => $modelAuthItemChildForm,
        ]);
    }

    /**
     * Изменить наследования RBAC
     * @return string
     */
    public function actionUpdateAuthItemChild($parent)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthItemChildForm = AuthItemChildForm::findOne(['parent' => $parent]);

        if ($modelAuthItemChildForm->load(Yii::$app->request->post()) && $modelAuthItemChildForm->save()) {
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

        if ($modelAuthItemChildForm->errors) {
            return $this->renderAjax('_form-auth-item-child', [
                'modelAuthItemChildForm' => $modelAuthItemChildForm,
            ]);
        }

        return $this->renderAjax('modal-auth-item-child', [
            'modelAuthItemChildForm' => $modelAuthItemChildForm,
        ]);
    }

    /**
     * Просмотр наследования RBAC
     * @return string
     */
    public function actionViewAuthItemChild($parent)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthItemChildForm = AuthItemChildForm::findOne(['parent' => $parent]);

        return $this->renderAjax('view-auth-item-child', [
            'modelAuthItemChildForm' => $modelAuthItemChildForm,
        ]);
    }

    /**
     * Подтверждение удаления наследования RBAC
     * @return string
     */
    public function actionConfirmDeleteAuthItemChild($parent)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('confirm-delete-auth-item-child', [
            'parent' => $parent,
        ]);
    }

    /**
     * Удаление наследования RBAC
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteAuthItemChild($parent)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthItemChildForm = AuthItemChildForm::findOne(['parent' => $parent]);

        try {
            $modelAuthItemChildForm->delete();
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

        $allAuthItemChildSearch = new AuthItemChildSearch();
        $dataProviderAuthItemChildSearch = $allAuthItemChildSearch->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_grid-auth-item-child-block', [
            'allAuthItemChildSearch' => $allAuthItemChildSearch,
            'dataProviderAuthItemChildSearch' => $dataProviderAuthItemChildSearch,
        ]);
    }

    /**
     * Обновление блока правил
     * @return string
     */
    public function actionRefreshAuthRule()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $allAuthRuleSearch = new AuthRuleSearch();
        $dataProviderAuthRuleSearch = $allAuthRuleSearch->search(Yii::$app->request->queryParams);

        return $this->render('_grid-auth-rule-block', [
            'allAuthRuleSearch' => $allAuthRuleSearch,
            'dataProviderAuthRuleSearch' => $dataProviderAuthRuleSearch,
        ]);
    }

    /**
     * Создание правила
     * @return string
     */
    public function actionCreateAuthRule()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthRuleForm = new AuthRuleForm();

        if ($modelAuthRuleForm->load(Yii::$app->request->post()) && $modelAuthRuleForm->save()) {
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

        if ($modelAuthRuleForm->errors) {
            return $this->renderAjax('_form-auth-rule', [
                'modelAuthRuleForm' => $modelAuthRuleForm,
            ]);
        }

        return $this->renderAjax('modal-auth-rule', [
            'modelAuthRuleForm' => $modelAuthRuleForm,
        ]);
    }

    /**
     * Изменение правила
     * @return string
     */
    public function actionUpdateAuthRule($name)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthRuleForm = AuthRuleForm::findOne(['name' => $name]);

        if ($modelAuthRuleForm->load(Yii::$app->request->post()) && $modelAuthRuleForm->save()) {
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

        if ($modelAuthRuleForm->errors) {
            return $this->renderAjax('_form-auth-rule', [
                'modelAuthRuleForm' => $modelAuthRuleForm,
            ]);
        }

        return $this->renderAjax('modal-auth-rule', [
            'modelAuthRuleForm' => $modelAuthRuleForm,
        ]);
    }

    /**
     * Просмотр правила
     * @return string
     */
    public function actionViewAuthRule($name)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthRuleForm = AuthRuleForm::findOne(['name' => $name]);

        return $this->renderAjax('view-auth-rule', [
            'modelAuthRuleForm' => $modelAuthRuleForm,
        ]);
    }

    /**
     * Подтверждение удаления роли или разрешения
     * @return string
     */
    public function actionConfirmDeleteAuthRule($name)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('confirm-delete-auth-rule', [
            'name' => $name,
        ]);
    }

    /**
     * Удаления роли или разрешения
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteAuthRule($name)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelAuthRuleForm = AuthRuleForm::findOne(['name' => $name]);

        try {
            $modelAuthRuleForm->delete();
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

        $allAuthRuleSearch = new AuthRuleSearch();
        $dataProviderAuthRuleSearch = $allAuthRuleSearch->search(Yii::$app->request->queryParams);

        return $this->render('_grid-auth-rule-block', [
            'allAuthRuleSearch' => $allAuthRuleSearch,
            'dataProviderAuthRuleSearch' => $dataProviderAuthRuleSearch,
        ]);
    }
}
