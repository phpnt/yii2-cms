<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 14.02.2019
 * Time: 14:21
 */

namespace backend\modules\document\controllers;

use common\models\forms\TemplateForm;
use common\models\forms\TemplateViewForm;
use Yii;
use yii\base\ErrorException;
use yii\base\InlineAction;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class TemplateViewManageController extends Controller
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
    public function actionRefreshTemplates($template_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/document/manage/index']);
        }

        $modelTemplateForm = TemplateForm::findOne($template_id);

        return $this->renderAjax('__template-buttons', [
            'modelTemplateForm' => $modelTemplateForm
        ]);
    }

    /**
     * @return mixed
     */
    public function actionIndex($template_id, $type)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/document/manage/index']);
        }

        $modelTemplateViewForm = TemplateViewForm::findOne([
            'type' => $type,
            'template_id' => $template_id,
        ]);

        if (!$modelTemplateViewForm) {
            $modelTemplateViewForm = new TemplateViewForm();
            $modelTemplateViewForm->type = $type;
            $modelTemplateViewForm->view = ' ';
            $modelTemplateViewForm->template_id = $template_id;
        }



        if ($modelTemplateViewForm->load(Yii::$app->request->post()) && $modelTemplateViewForm->save()) {
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

        if ($modelTemplateViewForm->errors) {
            return $this->renderAjax('_form-template-view', [
                'modelTemplateViewForm' => $modelTemplateViewForm,
            ]);
        }

        return $this->renderAjax('modal-template-view', [
            'modelTemplateViewForm' => $modelTemplateViewForm,
        ]);
    }

    /**
     * Удалить шаблон представления
     *
     * @return mixed
     * @throws ErrorException
     */
    public function actionDelete($template_id, $id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/document/manage/index']);
        }

        $modelTemplateViewForm = TemplateViewForm::findOne($id);
        try {
            $modelTemplateViewForm->delete();
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

        $modelTemplateForm = TemplateForm::findOne($template_id);

        return $this->renderAjax('__template-buttons', [
            'modelTemplateForm' => $modelTemplateForm
        ]);
    }
}