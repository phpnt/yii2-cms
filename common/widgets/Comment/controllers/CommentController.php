<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 22:05
 */

namespace common\widgets\Comment\controllers;

use common\models\Constants;
use common\models\forms\CommentForm;
use Yii;
use yii\base\ErrorException;
use yii\db\StaleObjectException;
use yii\web\Controller;

class CommentController extends Controller
{
    /**
     * Изменение комментария
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionRefreshComment($document_id, $access_answers = false)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        $comments = (new \yii\db\Query())
            ->select(['*'])
            ->from('comment')
            ->where([
                'document_id' => $document_id,
                'parent_id' => null,
            ])
            ->andWhere(['!=', 'status', Constants::STATUS_DOC_BLOCKED])
            ->all();

        return $this->renderAjax('@frontend/views/templates/comment/index', [
            'document_id' => $document_id,
            'comments' => $comments,
            'access_answers' => $access_answers,
        ]);
    }

    /**
     * Создание комментария
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCreateComment($document_id, $comment_id = null, $access_answers = false)
    {
        if (!Yii::$app->request->isPjax || Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelCommentForm = new CommentForm();
        $modelCommentForm->scenario = 'update-comment';
        $modelCommentForm->document_id = $document_id;
        $modelCommentForm->parent_id = $comment_id;

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

        return $this->renderAjax('@frontend/views/templates/comment/_form-comment', [
            'document_id' => $document_id,
            'comment_id' => $comment_id,
            'modelCommentForm' => $modelCommentForm,
            'access_answers' => $access_answers,
        ]);
    }

    /**
     * Изменение комментария
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionUpdateComment($document_id, $comment_id, $access_answers = false)
    {
        if (!Yii::$app->request->isPjax || Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelCommentForm = CommentForm::findOne($comment_id);
        $modelCommentForm->scenario = 'update-comment';

        if ($modelCommentForm->user_id == Yii::$app->user->id) {
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
        } else {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'danger',
                    'icon' => 'glyphicon glyphicon-ban',
                    'message' => Yii::t('app', 'Ошибка'),
                ]
            );
        }

        return $this->renderAjax('@frontend/views/templates/comment/_form-comment', [
            'document_id' => $document_id,
            'comment_id' => $comment_id,
            'modelCommentForm' => $modelCommentForm,
            'access_answers' => $access_answers,
        ]);
    }

    /**
     * Подтверждение удаления комментария
     * @return string
     */
    public function actionConfirmDeleteComment($document_id, $comment_id, $access_answers = false)
    {
        if (!Yii::$app->request->isPjax || Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('@frontend/views/templates/comment/confirm-delete-comment', [
            'document_id' => $document_id,
            'comment_id' => $comment_id,
            'access_answers' => $access_answers,
        ]);
    }

    /**
     * Удаление комментария
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteComment($document_id, $comment_id, $access_answers = false)
    {
        if (!Yii::$app->request->isPjax || Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        $modelCommentForm = CommentForm::findOne($comment_id);

        if ($modelCommentForm->user_id == Yii::$app->user->id) {
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
        } else {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'danger',
                    'icon' => 'glyphicon glyphicon-ban',
                    'message' => Yii::t('app', 'Ошибка'),
                ]
            );
        }

        $comments = (new \yii\db\Query())
            ->select(['*'])
            ->from('comment')
            ->where([
                'document_id' => $document_id,
                'parent_id' => null,
            ])
            ->andWhere(['!=', 'status', Constants::STATUS_DOC_BLOCKED])
            ->all();

        return $this->renderAjax('@frontend/views/templates/comment/index', [
            'document_id' => $document_id,
            'comments' => $comments,
            'access_answers' => $access_answers,
        ]);
    }
}