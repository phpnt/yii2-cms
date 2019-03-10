<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 22:05
 */

namespace common\widgets\Rating\controllers;

use common\models\Constants;
use common\models\forms\DocumentForm;
use Yii;
use yii\web\Controller;

class RatingController extends Controller
{
    /**
     * Управление кнопкой "Нравиться"
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionLike($document_id, $dislike = false)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'rating',
            ])
            ->one();

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            // удаляет dislike, если есть
            DocumentForm::deleteAll([
                'annotation' => 'dislike',
                'item_id' => $document_id,
                'ip' => Yii::$app->request->userIP,
                'user_agent' => Yii::$app->request->userAgent
            ]);
            // с одним IP обновляется раз в сутки
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'annotation' => 'like',
                    'item_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->andWhere(['>', 'updated_at', time() - (60 * 60 * 24)])
                ->one();

            if (!$data) {
                $modelDocumentForm = new DocumentForm();
                $modelDocumentForm->scenario = 'create-element';
                $time = time();
                $modelDocumentForm->name = 'like-' . $time;
                $modelDocumentForm->alias = 'like-' . $time;
                $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
                $modelDocumentForm->template_id = $parent['template_id'];
                $modelDocumentForm->parent_id = $parent['id'];
                $modelDocumentForm->item_id = $document_id;
                $modelDocumentForm->annotation = 'like';
                $modelDocumentForm->content = 'like';
                if (!$modelDocumentForm->save()) {
                    dd($modelDocumentForm->errors);
                }
            } else {
                DocumentForm::deleteAll([
                    'annotation' => 'like',
                    'item_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
        } else {
            // удаляет dislike, если есть
            DocumentForm::deleteAll([
                'annotation' => 'dislike',
                'item_id' => $document_id,
                'created_by' => Yii::$app->user->id,
            ]);

            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'annotation' => 'like',
                    'item_id' => $document_id,
                    'created_by' => Yii::$app->user->id,
                ])->one();

            if (!$data) {
                $modelDocumentForm = new DocumentForm();
                $modelDocumentForm->scenario = 'create-element';
                $time = time();
                $modelDocumentForm->name = 'like-' . $time;
                $modelDocumentForm->alias = 'like-' . $time;
                $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
                $modelDocumentForm->template_id = $parent['template_id'];
                $modelDocumentForm->parent_id = $parent['id'];
                $modelDocumentForm->item_id = $document_id;
                $modelDocumentForm->annotation = 'like';
                $modelDocumentForm->content = 'like';
                if (!$modelDocumentForm->save()) {
                    dd($modelDocumentForm->errors);
                }
            } else {
                DocumentForm::deleteAll([
                    'annotation' => 'like',
                    'item_id' => $document_id,
                    'created_by' => Yii::$app->user->id,
                ]);
            }
        }

        $likes = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'like',
                'parent_id' => $parent['id'],
                'item_id' => $document_id,
            ])
            ->count();

        if (!$dislike) {
            return $this->renderAjax('@frontend/views/templates/control/blocks/rating/like', [
                'document_id' => $document_id,
                'likes' => $likes
            ]);
        }

        $dislikes = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'dislike',
                'parent_id' => $parent['id'],
                'item_id' => $document_id,
            ])
            ->count();

        return $this->renderAjax('@frontend/views/templates/control/blocks/rating/like_and_dislike', [
            'document_id' => $document_id,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ]);
    }

    /**
     * Управление кнопкой "Не нравиться"
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionDislike($document_id, $like = false)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'rating',
            ])
            ->one();

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            // удаляет like, если есть
            DocumentForm::deleteAll([
                'annotation' => 'like',
                'item_id' => $document_id,
                'ip' => Yii::$app->request->userIP,
                'user_agent' => Yii::$app->request->userAgent
            ]);

            // с одним IP обновляется раз в сутки
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'annotation' => 'dislike',
                    'item_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->andWhere(['>', 'updated_at', time() - (60 * 60 * 24)])
                ->one();

            if (!$data) {
                $modelDocumentForm = new DocumentForm();
                $modelDocumentForm->scenario = 'create-element';
                $time = time();
                $modelDocumentForm->name = 'dislike-' . $time;
                $modelDocumentForm->alias = 'dislike-' . $time;
                $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
                $modelDocumentForm->template_id = $parent['template_id'];
                $modelDocumentForm->parent_id = $parent['id'];
                $modelDocumentForm->item_id = $document_id;
                $modelDocumentForm->annotation = 'dislike';
                $modelDocumentForm->content = 'dislike';
                if (!$modelDocumentForm->save()) {
                    dd($modelDocumentForm->errors);
                }
            } else {
                DocumentForm::deleteAll([
                    'annotation' => 'dislike',
                    'item_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
        } else {
            // удаляет dislike, если есть
            DocumentForm::deleteAll([
                'annotation' => 'like',
                'item_id' => $document_id,
                'created_by' => Yii::$app->user->id,
            ]);

            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'annotation' => 'dislike',
                    'item_id' => $document_id,
                    'created_by' => Yii::$app->user->id,
                ])->one();

            if (!$data) {
                $modelDocumentForm = new DocumentForm();
                $modelDocumentForm->scenario = 'create-element';
                $time = time();
                $modelDocumentForm->name = 'dislike-' . $time;
                $modelDocumentForm->alias = 'dislike-' . $time;
                $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
                $modelDocumentForm->template_id = $parent['template_id'];
                $modelDocumentForm->parent_id = $parent['id'];
                $modelDocumentForm->item_id = $document_id;
                $modelDocumentForm->annotation = 'dislike';
                $modelDocumentForm->content = 'dislike';
                if (!$modelDocumentForm->save()) {
                    dd($modelDocumentForm->errors);
                }
            } else {
                DocumentForm::deleteAll([
                    'annotation' => 'dislike',
                    'item_id' => $document_id,
                    'created_by' => Yii::$app->user->id,
                ]);
            }
        }

        $dislikes = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'dislike',
                'parent_id' => $parent['id'],
                'item_id' => $document_id,
            ])
            ->count();

        if (!$like) {
            return $this->renderAjax('@frontend/views/templates/control/blocks/rating/like', [
                'document_id' => $document_id,
                'dislikes' => $dislikes,
            ]);
        }

        $likes = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'like',
                'parent_id' => $parent['id'],
                'item_id' => $document_id,
            ])
            ->count();

        return $this->renderAjax('@frontend/views/templates/control/blocks/rating/like_and_dislike', [
            'document_id' => $document_id,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ]);
    }

    /**
     * Управление процентным рейтингом
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionSetPercent($document_id, $value)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        $stars_number = Yii::$app->request->post('stars_number');
        $star_cost = Yii::$app->request->post('star_cost');

        // если ошибка
        if (($value < 10 || $value > 100) ||
            (!$stars_number && $stars_number < 2 || $stars_number > 10) ||
            (!$star_cost && $star_cost < 10 || $star_cost > 50)) {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'danger',
                    'icon' => 'glyphicon glyphicon-ban',
                    'message' => Yii::t('app', 'Ошибка'),
                ]
            );
            return $this->goHome();
        }

        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'rating',
            ])
            ->one();

        // записываем результат
        if (Yii::$app->user->isGuest) {
            // с одним IP обновляется раз в сутки
            $modelDocumentForm = DocumentForm::find()
                ->where([
                    'annotation' => 'stars',
                    'item_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->andWhere(['>', 'updated_at', time() - (60 * 60 * 24)])
                ->one();

            if (!$modelDocumentForm) {
                $modelDocumentForm = new DocumentForm();
                $modelDocumentForm->scenario = 'create-element';
                $time = time();
                $modelDocumentForm->name = 'stars-' . $time;
                $modelDocumentForm->alias = 'stars-' . $time;
                $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
                $modelDocumentForm->template_id = $parent['template_id'];
                $modelDocumentForm->parent_id = $parent['id'];
                $modelDocumentForm->item_id = $document_id;
                $modelDocumentForm->annotation = 'stars';
                $modelDocumentForm->content = $value;
                if (!$modelDocumentForm->save()) {
                    dd($modelDocumentForm->errors);
                }
            } else {
                $modelDocumentForm->content = $value;
                $modelDocumentForm->save();
            }
        } else {
            $modelDocumentForm = DocumentForm::find()
                ->where([
                    'annotation' => 'stars',
                    'item_id' => $document_id,
                    'created_by' => Yii::$app->user->id
                ])
                ->one();

            if (!$modelDocumentForm) {
                $modelDocumentForm = new DocumentForm();
                $modelDocumentForm->scenario = 'create-element';
                $time = time();
                $modelDocumentForm->name = 'stars-' . $time;
                $modelDocumentForm->alias = 'stars-' . $time;
                $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
                $modelDocumentForm->template_id = $parent['template_id'];
                $modelDocumentForm->parent_id = $parent['id'];
                $modelDocumentForm->item_id = $document_id;
                $modelDocumentForm->annotation = 'stars';
                $modelDocumentForm->content = $value;
                if (!$modelDocumentForm->save()) {
                    dd($modelDocumentForm->errors);
                }
            } else {
                $modelDocumentForm->content = $value;
                $modelDocumentForm->save();
            }
        }

        // подсчет процентов
        $data = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'stars',
                'parent_id' => $parent['id'],
                'item_id' => $document_id,
            ])
            ->all();

        $votes_number = count($data);

        $percent_count = 0;
        $i = 0;
        foreach ($data as $item) {
            $percent_count = $percent_count + (int) $item['content'];
            $i++;
        }
        if ($i == 0) {
            $i = 1;
        }
        $percent_count = $percent_count / $i;

        return $this->renderAjax('@frontend/views/templates/control/blocks/rating/percentage', [
            'document_id' => $document_id,
            'percent_count' => $percent_count,
            'stars_number' => $stars_number,
            'star_cost' => $star_cost,
            'votes_number' => $votes_number
        ]);
    }

    /**
     * Управление кнопкой "Нравиться" для комментариев
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCommentLike($comment_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'rating',
            ])
            ->one();

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            // удаляет dislike, если есть
            DocumentForm::deleteAll([
                'annotation' => 'dislike',
                'item_id' => $comment_id,
                'ip' => Yii::$app->request->userIP,
                'user_agent' => Yii::$app->request->userAgent
            ]);
            // с одним IP обновляется раз в сутки
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'annotation' => 'like',
                    'item_id' => $comment_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->andWhere(['>', 'updated_at', time() - (60 * 60 * 24)])
                ->one();

            if (!$data) {
                $modelDocumentForm = new DocumentForm();
                $modelDocumentForm->scenario = 'create-element';
                $time = time();
                $modelDocumentForm->name = 'like-' . $time;
                $modelDocumentForm->alias = 'like-' . $time;
                $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
                $modelDocumentForm->template_id = $parent['template_id'];
                $modelDocumentForm->parent_id = $parent['id'];
                $modelDocumentForm->item_id = $comment_id;
                $modelDocumentForm->annotation = 'like';
                $modelDocumentForm->content = 'like';
                if (!$modelDocumentForm->save()) {
                    dd($modelDocumentForm->errors);
                }
            } else {
                DocumentForm::deleteAll([
                    'annotation' => 'like',
                    'item_id' => $comment_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
        } else {
            // удаляет dislike, если есть
            DocumentForm::deleteAll([
                'annotation' => 'dislike',
                'item_id' => $comment_id,
                'created_by' => Yii::$app->user->id,
            ]);

            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'annotation' => 'like',
                    'item_id' => $comment_id,
                    'created_by' => Yii::$app->user->id,
                ])->one();

            if (!$data) {
                $modelDocumentForm = new DocumentForm();
                $modelDocumentForm->scenario = 'create-element';
                $time = time();
                $modelDocumentForm->name = 'like-' . $time;
                $modelDocumentForm->alias = 'like-' . $time;
                $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
                $modelDocumentForm->template_id = $parent['template_id'];
                $modelDocumentForm->parent_id = $parent['id'];
                $modelDocumentForm->item_id = $comment_id;
                $modelDocumentForm->annotation = 'like';
                $modelDocumentForm->content = 'like';
                if (!$modelDocumentForm->save()) {
                    dd($modelDocumentForm->errors);
                }
            } else {
                DocumentForm::deleteAll([
                    'annotation' => 'like',
                    'item_id' => $comment_id,
                    'created_by' => Yii::$app->user->id,
                ]);
            }
        }

        $likes = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'like',
                'parent_id' => $parent['id'],
                'item_id' => $comment_id,
            ])
            ->count();

        $dislikes = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'dislike',
                'parent_id' => $parent['id'],
                'item_id' => $comment_id,
            ])
            ->count();

        return $this->renderAjax('@frontend/views/templates/control/blocks/rating/comment-rating', [
            'comment_id' => $comment_id,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ]);
    }

    /**
     * Управление кнопкой "Не нравиться"
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCommentDislike($comment_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'rating',
            ])
            ->one();

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            // удаляет like, если есть
            DocumentForm::deleteAll([
                'annotation' => 'like',
                'item_id' => $comment_id,
                'ip' => Yii::$app->request->userIP,
                'user_agent' => Yii::$app->request->userAgent
            ]);

            // с одним IP обновляется раз в сутки
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'annotation' => 'dislike',
                    'item_id' => $comment_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->andWhere(['>', 'updated_at', time() - (60 * 60 * 24)])
                ->one();

            if (!$data) {
                $modelDocumentForm = new DocumentForm();
                $modelDocumentForm->scenario = 'create-element';
                $time = time();
                $modelDocumentForm->name = 'dislike-' . $time;
                $modelDocumentForm->alias = 'dislike-' . $time;
                $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
                $modelDocumentForm->template_id = $parent['template_id'];
                $modelDocumentForm->parent_id = $parent['id'];
                $modelDocumentForm->item_id = $comment_id;
                $modelDocumentForm->annotation = 'dislike';
                $modelDocumentForm->content = 'dislike';
                if (!$modelDocumentForm->save()) {
                    dd($modelDocumentForm->errors);
                }
            } else {
                DocumentForm::deleteAll([
                    'annotation' => 'dislike',
                    'item_id' => $comment_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
        } else {
            // удаляет dislike, если есть
            DocumentForm::deleteAll([
                'annotation' => 'like',
                'item_id' => $comment_id,
                'created_by' => Yii::$app->user->id,
            ]);

            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'annotation' => 'dislike',
                    'item_id' => $comment_id,
                    'created_by' => Yii::$app->user->id,
                ])->one();

            if (!$data) {
                $modelDocumentForm = new DocumentForm();
                $modelDocumentForm->scenario = 'create-element';
                $time = time();
                $modelDocumentForm->name = 'dislike-' . $time;
                $modelDocumentForm->alias = 'dislike-' . $time;
                $modelDocumentForm->status = Constants::STATUS_DOC_WAIT;
                $modelDocumentForm->template_id = $parent['template_id'];
                $modelDocumentForm->parent_id = $parent['id'];
                $modelDocumentForm->item_id = $comment_id;
                $modelDocumentForm->annotation = 'dislike';
                $modelDocumentForm->content = 'dislike';
                if (!$modelDocumentForm->save()) {
                    dd($modelDocumentForm->errors);
                }
            } else {
                DocumentForm::deleteAll([
                    'annotation' => 'dislike',
                    'item_id' => $comment_id,
                    'created_by' => Yii::$app->user->id,
                ]);
            }
        }

        $likes = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'like',
                'parent_id' => $parent['id'],
                'item_id' => $comment_id,
            ])
            ->count();

        $dislikes = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'dislike',
                'parent_id' => $parent['id'],
                'item_id' => $comment_id,
            ])
            ->count();

        return $this->renderAjax('@frontend/views/templates/control/blocks/rating/comment-rating', [
            'comment_id' => $comment_id,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ]);
    }
}