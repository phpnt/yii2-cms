<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.09.2018
 * Time: 13:42
 */

namespace backend\modules\document\controllers;

use common\models\forms\TemplateForm;
use common\models\search\TemplateSearch;
use yii\base\ErrorException;
use yii\base\InlineAction;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;

class TemplateManageController extends Controller
{
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
     * Обновление блока шаблонов
     * @return string
     */
    public function actionRefreshTemplates()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelTemplateSearch = new TemplateSearch();
        $dataProviderTemplateSearch = $modelTemplateSearch->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_grid-templates-block', [
            'modelTemplateSearch' => $modelTemplateSearch,
            'dataProviderTemplateSearch' => $dataProviderTemplateSearch,
        ]);
    }

    /**
     * Создание шаблона
     * @return string
     */
    public function actionCreateTemplate()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelTemplateForm = new TemplateForm();
        $modelTemplateForm->scenario = 'create-template';

        if ($modelTemplateForm->load(Yii::$app->request->post()) && $modelTemplateForm->save()) {
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

        if ($modelTemplateForm->errors) {
            return $this->renderAjax('_form-template', [
                'modelTemplateForm' => $modelTemplateForm,
            ]);
        }

        return $this->renderAjax('modal-template', [
            'modelTemplateForm' => $modelTemplateForm,
        ]);
    }

    /**
     * Изменение шаблона
     * @return string
     */
    public function actionUpdateTemplate($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelTemplateForm = TemplateForm::findOne($id);
        $modelTemplateForm->scenario = 'create-template';

        if ($modelTemplateForm->load(Yii::$app->request->post()) && $modelTemplateForm->save()) {
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

        if ($modelTemplateForm->errors) {
            return $this->renderAjax('_form-template', [
                'modelTemplateForm' => $modelTemplateForm,
            ]);
        }

        return $this->renderAjax('modal-template', [
            'modelTemplateForm' => $modelTemplateForm,
        ]);
    }

    /**
     * Просмотр шаблона
     * @return string
     */
    public function actionViewTemplate($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelTemplateForm = TemplateForm::findOne($id);

        return $this->renderAjax('view-template', [
            'modelTemplateForm' => $modelTemplateForm,
        ]);
    }

    /**
     * Подтверждение удаления шаблона
     * @return string
     */
    public function actionConfirmDeleteTemplate($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('confirm-delete-template', [
            'id' => $id,
        ]);
    }

    /**
     * Удаления шаблона
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteTemplate($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelTemplateForm = TemplateForm::findOne($id);

        try {
            $modelTemplateForm->delete();
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

        $modelTemplateSearch = new TemplateSearch();
        $dataProviderTemplateSearch = $modelTemplateSearch->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_grid-templates-block', [
            'modelTemplateSearch' => $modelTemplateSearch,
            'dataProviderTemplateSearch' => $dataProviderTemplateSearch,
        ]);
    }
}