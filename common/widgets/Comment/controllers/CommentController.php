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
use common\models\forms\DocumentForm;
use function GuzzleHttp\Psr7\str;
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
    public function actionRefreshComment($item_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'comments',
            ])
            ->one();

        $comments = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->leftJoin('value_int', 'value_int.document_id = document.id')
            ->where([
                'parent_id' => $parent['id'],
                'item_id' => $item_id,
                'value_int.id' => null,
            ])
            ->andWhere(['!=', 'status', Constants::STATUS_DOC_BLOCKED])
            ->all();

        return $this->renderAjax('@frontend/views/templates/control/blocks/comment/index', [
            'item_id' => $item_id,
            'comments' => $comments,
        ]);
    }

    /**
     * Создание комментария
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCreateComment($item_id, $comment_id = null)
    {
        /*if (!Yii::$app->request->isPjax || Yii::$app->user->isGuest) {
            return $this->goHome();
        }*/

        // если нажата ссылка главного меню
        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'comments',
            ])
            ->one();

        $modelDocumentForm = new DocumentForm();
        $modelDocumentForm->scenario = 'create-element';
        $time = time();
        $modelDocumentForm->name = 'comment-' . $time;
        $modelDocumentForm->alias = 'comment-' . $time;
        $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
        $modelDocumentForm->template_id = $parent['template_id'];
        $modelDocumentForm->parent_id = $parent['id'];
        $modelDocumentForm->item_id = $item_id;
        $modelDocumentForm->field_id_prefix = strval($item_id);
        $modelDocumentForm->comment_id = $comment_id;

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

        return $this->renderAjax('@frontend/views/templates/control/blocks/comment/_form-comment', [
            'item_id' => $item_id,
            'modelDocumentForm' => $modelDocumentForm,
        ]);
    }

    /**
     * Изменение комментария
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionUpdateComment($id)
    {
        /*if (!Yii::$app->request->isPjax || Yii::$app->user->isGuest) {
            return $this->goHome();
        }*/

        $modelDocumentForm = DocumentForm::findOne($id);
        $modelDocumentForm->scenario = 'update-element';
        $modelDocumentForm->field_id_prefix = strval($modelDocumentForm->item_id);

        /*if ($modelDocumentForm->created_by != Yii::$app->user->id) {
            return $this->goHome();
        }*/

        if ($modelDocumentForm->created_by == Yii::$app->user->id) {
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

        return $this->renderAjax('@frontend/views/templates/control/blocks/comment/_form-comment', [
            'item_id' => $modelDocumentForm->item_id,
            'modelDocumentForm' => $modelDocumentForm,
        ]);
    }

    /**
     * Подтверждение удаления комментария
     * @return string
     */
    public function actionConfirmDeleteComment($id, $access_answers = false)
    {
        if (!Yii::$app->request->isPjax || Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('@frontend/views/templates/control/blocks/comment/confirm-delete-comment', [
            'id' => $id,
            'access_answers' => $access_answers,
        ]);
    }

    /**
     * Удаление комментария
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteComment($id)
    {
        if (!Yii::$app->request->isPjax || Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        $modelDocumentForm = DocumentForm::findOne($id);

        if ($modelDocumentForm->created_by == Yii::$app->user->id) {
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

        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'comments',
            ])
            ->one();

        $comments = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->leftJoin('value_int', 'value_int.document_id = document.id')
            ->where([
                'parent_id' => $parent['id'],
                'item_id' => $modelDocumentForm->item_id,
                'value_int.id' => null,
            ])
            ->andWhere(['!=', 'status', Constants::STATUS_DOC_BLOCKED])
            ->all();

        return $this->renderAjax('@frontend/views/templates/control/blocks/comment/index', [
            'item_id' => $modelDocumentForm->item_id,
            'comments' => $comments
        ]);
    }
}