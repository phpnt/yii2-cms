<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 24.09.2018
 * Time: 5:14
 */

namespace backend\modules\document\controllers;

use common\models\forms\FieldForm;
use common\models\forms\TemplateForm;
use Yii;
use yii\base\ErrorException;
use yii\base\InlineAction;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class FieldManageController extends Controller
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
     * Обновление блока полей шаблона
     * @return string
     */
    public function actionRefreshFields($template_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelTemplateForm = TemplateForm::findOne($template_id);

        return $this->renderAjax('__fields_of_template', [
            'manyFieldForm' => $modelTemplateForm->fields,
            'key' => $modelTemplateForm->id,
        ]);
    }

    /**
     * Обновление блока полей шаблона
     * @return string
     */
    public function actionRefreshFieldForm($id = null)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        if ($id) {
            $modelFieldForm = FieldForm::findOne($id);
            $modelFieldForm->scenario = 'update-field';
        } else {
            $modelFieldForm = new FieldForm();
            $modelFieldForm->scenario = 'create-field';
        }

        $modelFieldForm->load(Yii::$app->request->post());

        return $this->renderAjax('_form-field', [
            'modelFieldForm' => $modelFieldForm,
        ]);
    }

    /**
     * Создание поля
     *
     * @return mixed
     */
    public function actionCreateField($template_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelFieldForm = new FieldForm();
        $modelFieldForm->scenario = 'create-field';
        $modelFieldForm->template_id = $template_id;

        if ($modelFieldForm->load(Yii::$app->request->post()) && $modelFieldForm->save()) {
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

        if ($modelFieldForm->errors) {
            return $this->renderAjax('_form-field', [
                'modelFieldForm' => $modelFieldForm,
            ]);
        }

        return $this->renderAjax('modal-field', [
            'modelFieldForm' => $modelFieldForm,
        ]);
    }

    /**
     * Изменение поля
     * @return string
     */
    public function actionUpdateField($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelFieldForm = FieldForm::findOne($id);
        $modelFieldForm->scenario = 'update-field';

        if ($modelFieldForm->load(Yii::$app->request->post()) && $modelFieldForm->save()) {
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

        if ($modelFieldForm->errors) {
            return $this->renderAjax('_form-field', [
                'modelFieldForm' => $modelFieldForm,
            ]);
        }

        return $this->renderAjax('modal-field', [
            'modelFieldForm' => $modelFieldForm,
        ]);
    }

    /**
     * Просмотр поля
     * @return string
     */
    public function actionViewField($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelFieldForm = FieldForm::findOne($id);

        return $this->renderAjax('view-field', [
            'modelFieldForm' => $modelFieldForm,
        ]);
    }

    /**
     * Подтверждение удаления поля
     * @return string
     */
    public function actionConfirmDeleteField($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelFieldForm = FieldForm::findOne($id);

        return $this->renderAjax('confirm-delete-field', [
            'id' => $id,
            'template_id' => $modelFieldForm->template_id
        ]);
    }

    /**
     * Удаления поля
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteField($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelFieldForm = FieldForm::findOne($id);
        $modelTemplateForm = TemplateForm::findOne($modelFieldForm->template_id);

        try {
            $modelFieldForm->delete();
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

        return $this->renderAjax('__fields_of_template', [
            'manyFieldForm' => $modelTemplateForm->fields,
            'key' => $modelTemplateForm->id,
        ]);
    }
}