<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.09.2018
 * Time: 14:13
 */

namespace backend\modules\document\controllers;

use common\models\forms\DocumentForm;
use common\models\search\DocumentSearch;
use Yii;
use yii\base\ErrorException;
use yii\base\InlineAction;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class FolderManageController extends Controller
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
     * Просмотр содержимого папок
     * @return mixed
     */
    public function actionViewElements($id_folder)
    {
        /*if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }*/

        $modelDocumentForm = DocumentForm::findOne($id_folder);

        $modelDocumentSearch = new DocumentSearch();
        $modelDocumentSearch->parent_id = $id_folder;
        $dataProviderDocumentSearch = $modelDocumentSearch->searchElement(Yii::$app->request->queryParams);

        return $this->renderAjax('@backend/modules/document/views/element-manage/_grid-elements-block', [
            'modelDocumentForm' => $modelDocumentForm,
            'modelDocumentSearch' => $modelDocumentSearch,
            'dataProviderDocumentSearch' => $dataProviderDocumentSearch,
        ]);
    }

    /**
     * Обновление блока папок
     * @return mixed
     */
    public function actionRefreshFolders()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelDocumentSearchFolder = new DocumentSearch();
        $modelDocumentSearchFolder->is_folder = 1;
        $dataProviderDocumentSearchFolders = $modelDocumentSearchFolder->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_tree-folders-block', [
            'modelDocumentSearchFolder' => $modelDocumentSearchFolder,
            'dataProviderDocumentSearchFolders' => $dataProviderDocumentSearchFolders,
        ]);
    }

    /**
     * Создание папки
     * @return mixed
     */
    public function actionCreateFolder($parent_id = null)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelDocumentForm = new DocumentForm();
        $modelDocumentForm->scenario = 'create-folder';
        $modelDocumentForm->is_folder = 1;
        $modelDocumentForm->parent_id = $parent_id;

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
            return $this->renderAjax('_form-folder', [
                'modelDocumentForm' => $modelDocumentForm,
            ]);
        }

        return $this->renderAjax('modal-folder', [
            'modelDocumentForm' => $modelDocumentForm,
        ]);
    }

    /**
     * Изменение папки
     * @return mixed
     */
    public function actionUpdateFolder($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelDocumentForm = DocumentForm::findOne($id);
        $modelDocumentForm->scenario = 'update-folder';
        $modelDocumentForm->is_folder = 1;

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
            return $this->renderAjax('_form-folder', [
                'modelDocumentForm' => $modelDocumentForm,
            ]);
        }

        return $this->renderAjax('modal-folder', [
            'modelDocumentForm' => $modelDocumentForm,
        ]);
    }

    /**
     * Просмотр папки
     * @return mixed
     */
    public function actionViewFolder($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelDocumentForm = DocumentForm::findOne($id);

        return $this->renderAjax('view-folder', [
            'modelDocumentForm' => $modelDocumentForm,
        ]);
    }

    /**
     * Подтверждение удаления папки
     * @return mixed
     */
    public function actionConfirmDeleteFolder($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('confirm-delete-folder', [
            'id' => $id,
        ]);
    }

    /**
     * Удаления папки
     *
     * @return mixed
     * @throws ErrorException
     */
    public function actionDeleteFolder($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelDocumentForm = DocumentForm::findOne($id);

        if ($modelDocumentForm->childs) {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'danger',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Внимание!!! Папка содержит элементы, удалите сперва их.'),
                ]
            );
        } else {
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
        }

        $modelDocumentSearchFolder = new DocumentSearch();
        $modelDocumentSearchFolder->is_folder = 1;
        $dataProviderDocumentSearchFolders = $modelDocumentSearchFolder->search(Yii::$app->request->queryParams);

        return $this->renderAjax('_tree-folders-block', [
            'modelDocumentSearchFolder' => $modelDocumentSearchFolder,
            'dataProviderDocumentSearchFolders' => $dataProviderDocumentSearchFolders,
        ]);
    }
}