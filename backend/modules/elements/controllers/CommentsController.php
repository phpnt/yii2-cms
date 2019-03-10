<?php

namespace backend\modules\elements\controllers;

use common\models\Constants;
use Yii;
use common\models\forms\DocumentForm;
use common\models\search\DocumentSearch;
use yii\base\ErrorException;
use yii\base\InlineAction;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `elements` module
 */
class CommentsController extends Controller
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

    public function actionIndex()
    {
        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'comments',
            ])
            ->one();

        $modelDocumentForm = DocumentForm::findOne($parent['id']);

        $modelDocumentSearch = new DocumentSearch();
        $modelDocumentSearch->is_folder = null;
        $modelDocumentSearch->parent_id = $parent['id'];
        $dataProviderDocumentSearch = $modelDocumentSearch->searchElement(Yii::$app->request->queryParams);
        $dataProviderDocumentSearch->query->orderBy(['id' => SORT_DESC]);

        return $this->render('index', [
            'modelDocumentForm' => $modelDocumentForm,
            'modelDocumentSearch' => $modelDocumentSearch,
            'dataProviderDocumentSearch' => $dataProviderDocumentSearch,
        ]);
    }

    /**
     * Обновление блока
     * @return string
     */
    public function actionRefresh($id_folder)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['elements/comments/index']);
        }

        $modelDocumentForm = DocumentForm::findOne($id_folder);

        $modelDocumentSearch = new DocumentSearch();
        $modelDocumentSearch->is_folder = null;
        $modelDocumentSearch->parent_id = $id_folder;
        $dataProviderDocumentSearch = $modelDocumentSearch->searchElement(Yii::$app->request->queryParams);
        $dataProviderDocumentSearch->query->orderBy(['id' => SORT_DESC]);

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
    public function actionCreate($id_folder)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/document/manage/index']);
        }

        $parentData = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['id' => $id_folder])
            ->one();

        $template_id = $parentData['template_id'];
        $parent_alias = $parentData['alias'];

        $modelDocumentForm = new DocumentForm();
        $modelDocumentForm->scenario = 'create-element';
        $modelDocumentForm->parent_id = $id_folder;
        $modelDocumentForm->parent_alias = $parent_alias;
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
    public function actionUpdate($id_document, $id_folder)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/document/manage/index']);
        }

        $parentData = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['id' => $id_folder])
            ->one();

        $parent_alias = $parentData['alias'];

        $modelDocumentForm = DocumentForm::findOne($id_document);
        $modelDocumentForm->scenario = 'update-element';
        $modelDocumentForm->parent_id = $id_folder;
        $modelDocumentForm->parent_alias = $parent_alias;

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
    public function actionView($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/document/manage/index']);
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
    public function actionConfirmDelete($id)
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
    public function actionDelete($id)
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
        $modelDocumentSearch->parent_id = $id_folder;
        $dataProviderDocumentSearch = $modelDocumentSearch->searchElement(Yii::$app->request->queryParams);
        $dataProviderDocumentSearch->query->orderBy(['id' => SORT_DESC]);

        return $this->renderAjax('_grid-elements-block', [
            'modelDocumentForm' => $modelDocumentForm,
            'modelDocumentSearch' => $modelDocumentSearch,
            'dataProviderDocumentSearch' => $dataProviderDocumentSearch,
        ]);
    }

    /**
     * Удаление комментари
     *
     * @return string
     * @throws ErrorException
     */
    public function actionCountUnchecked()
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'comments',
            ])
            ->one();

        $countComment = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'parent_id' => $parent['id'],
                'status' => Constants::STATUS_DOC_WAIT
            ])
            ->count();

        return $this->renderAjax('@common/widgets/Comment/views/index', [
            'countComment' => $countComment,
        ]);
    }
}
