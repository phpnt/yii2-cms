<?php

namespace backend\modules\comment\controllers;

use common\models\Constants;
use common\models\forms\CommentForm;
use common\models\search\CommentSearch;
use Yii;
use yii\base\ErrorException;
use yii\base\InlineAction;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `comment` module
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
        $modelCommentSearch = new CommentSearch();
        $dataProviderCommentSearch = $modelCommentSearch->search(Yii::$app->request->queryParams);
        $dataProviderCommentSearch->query->orderBy(['id' => SORT_DESC]);

        return $this->render('index', [
            'modelCommentSearch' => $modelCommentSearch,
            'dataProviderCommentSearch' => $dataProviderCommentSearch
        ]);
    }

    public function actionRefreshComment()
    {
        $modelCommentSearch = new CommentSearch();
        $dataProviderCommentSearch = $modelCommentSearch->search(Yii::$app->request->queryParams);
        $dataProviderCommentSearch->query->orderBy(['id' => SORT_DESC]);

        return $this->renderAjax('index', [
            'modelCommentSearch' => $modelCommentSearch,
            'dataProviderCommentSearch' => $dataProviderCommentSearch
        ]);
    }

    /**
     * Просмотр комментария
     * @return string
     */
    public function actionViewComment($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelCommentForm = CommentForm::findOne($id);

        return $this->renderAjax('view-comment', [
            'modelCommentForm' => $modelCommentForm,
        ]);
    }

    /**
     * Изменение комментария
     * @return string
     */
    public function actionUpdateComment($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelCommentForm = CommentForm::findOne($id);
        $modelCommentForm->scenario = 'update-comment';

        if ($modelCommentForm->load(Yii::$app->request->post()) && $modelCommentForm->save()) {
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

        if ($modelCommentForm->errors) {
            return $this->renderAjax('_form-comment', [
                'modelCommentForm' => $modelCommentForm,
            ]);
        }

        return $this->renderAjax('modal-comment', [
            'modelCommentForm' => $modelCommentForm,
        ]);
    }

    /**
     * Подтверждение удаления комментария
     * @return string
     */
    public function actionConfirmDeleteComment($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('confirm-delete-comment', [
            'id' => $id,
        ]);
    }

    /**
     * Удаление комментари
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteComment($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelCommentForm = CommentForm::findOne($id);

        try {
            $modelCommentForm->delete();
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

        $modelCommentSearch = new CommentSearch();
        $dataProviderCommentSearch = $modelCommentSearch->search(Yii::$app->request->queryParams);
        $dataProviderCommentSearch->query->orderBy(['id' => SORT_DESC]);

        return $this->renderAjax('index', [
            'modelCommentSearch' => $modelCommentSearch,
            'dataProviderCommentSearch' => $dataProviderCommentSearch
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

        // подсчет процентов
        $countComment = (new \yii\db\Query())
            ->from('comment')
            ->where(['status' => Constants::STATUS_DOC_WAIT])
            ->count();

        return $this->renderAjax('@common/widgets/UncheckedComments/views/index', [
            'countComment' => $countComment,
        ]);
    }
}
