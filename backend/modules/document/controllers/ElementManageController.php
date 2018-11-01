<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.09.2018
 * Time: 14:43
 */

namespace backend\modules\document\controllers;

use common\models\Constants;
use common\models\forms\DocumentForm;
use common\models\forms\ValueFileForm;
use common\models\search\DocumentSearch;
use Yii;
use yii\base\ErrorException;
use yii\base\InlineAction;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class ElementManageController extends Controller
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
     * Обновление блока документов
     * @return string
     */
    public function actionRefreshElements($id_folder)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelDocumentForm = DocumentForm::findOne($id_folder);

        $modelDocumentSearch = new DocumentSearch();
        $modelDocumentSearch->is_folder = null;
        $modelDocumentSearch->parent_id = $id_folder;
        $dataProviderDocumentSearch = $modelDocumentSearch->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_grid-elements-block', [
            'modelDocumentForm' => $modelDocumentForm,
            'modelDocumentSearch' => $modelDocumentSearch,
            'dataProviderDocumentSearch' => $dataProviderDocumentSearch,
        ]);
    }

    /**
     * Создание документа
     * @return string
     */
    public function actionCreateElement($id_folder)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelDocumentForm = DocumentForm::findOne($id_folder);
        $template_id = $modelDocumentForm->template_id;

        $modelDocumentForm = new DocumentForm();
        $modelDocumentForm->scenario = 'create-element';
        $modelDocumentForm->parent_id = $id_folder;
        $modelDocumentForm->template_id = $template_id;

        if ($modelDocumentForm->load(Yii::$app->request->post()) && $modelDocumentForm->save()) {
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

        if ($modelDocumentForm->errors) {
            return $this->renderAjax('_form-element', [
                'modelDocumentForm' => $modelDocumentForm,
            ]);
        }

        return $this->renderAjax('modal-element', [
            'modelDocumentForm' => $modelDocumentForm,
        ]);
    }

    /**
     * Изменение документа
     * @return string
     */
    public function actionUpdateElement($id_document, $id_folder)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelDocumentForm = DocumentForm::findOne($id_document);
        $modelDocumentForm->scenario = 'update-element';
        $modelDocumentForm->parent_id = $id_folder;

        if ($modelDocumentForm->load(Yii::$app->request->post()) && $modelDocumentForm->save()) {
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

        if ($modelDocumentForm->errors) {
            return $this->renderAjax('_form-element', [
                'modelDocumentForm' => $modelDocumentForm,
            ]);
        }

        return $this->renderAjax('modal-element', [
            'modelDocumentForm' => $modelDocumentForm,
        ]);
    }

    /**
     * Просмотр документа
     * @return string
     */
    public function actionViewElement($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelDocumentForm = DocumentForm::findOne($id);

        return $this->renderAjax('view-element', [
            'modelDocumentForm' => $modelDocumentForm,
        ]);
    }

    /**
     * Подтверждение удаления документа
     * @return string
     */
    public function actionConfirmDeleteElement($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('confirm-delete-element', [
            'id' => $id,
        ]);
    }

    /**
     * Удаления документа
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteElement($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelDocumentForm = DocumentForm::findOne($id);

        $id_folder = $modelDocumentForm->parent_id;

        try {
            $modelDocumentForm->delete();
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

        $modelDocumentForm = DocumentForm::findOne($id_folder);

        $modelDocumentSearch = new DocumentSearch();
        $modelDocumentSearch->is_folder = null;
        $modelDocumentSearch->parent_id = $id_folder;
        $dataProviderDocumentSearch = $modelDocumentSearch->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_grid-elements-block', [
            'modelDocumentForm' => $modelDocumentForm,
            'modelDocumentSearch' => $modelDocumentSearch,
            'dataProviderDocumentSearch' => $dataProviderDocumentSearch,
        ]);
    }

    /**
     * Подтверждение удаления документа
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteFile($id)
    {
        $modelValueFileForm = ValueFileForm::findOne($id);

        try {
            $modelValueFileForm->delete();
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

        if ($modelValueFileForm->type == Constants::FIELD_TYPE_FILE) {
            return $this->renderAjax('_file', ['modelValueFileForm' => null]);
        }

        $manyValueFileForm = ValueFileForm::findAll([
            'field_id' => $modelValueFileForm->field_id,
            'document_id' => $modelValueFileForm->document_id,
        ]);

        return $this->renderAjax('_files', ['manyValueFileForm' => $manyValueFileForm]);
    }
}