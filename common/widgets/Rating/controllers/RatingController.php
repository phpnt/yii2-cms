<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 22:05
 */

namespace common\widgets\Rating\controllers;

use common\models\forms\LikeForm;
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

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            if ($dislike) {
                // удаляет dislike, если есть
                LikeForm::deleteAll([
                    'like' => 0,
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
            // с одним IP обновляется раз в сутки
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('like')
                ->where([
                'document_id' => $document_id,
                'ip' => Yii::$app->request->userIP,
                'user_agent' => Yii::$app->request->userAgent
            ])->one();
            if (!$data) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->like = 1;
                $modelLikeForm->dislike = 0;
                $modelLikeForm->created_at = time();
                $modelLikeForm->document_id = $document_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->save();
            } else {
                LikeForm::deleteAll([
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
        } else {
            if ($dislike) {
                // удаляет dislike, если есть
                LikeForm::deleteAll([
                    'like' => 0,
                    'document_id' => $document_id,
                    'user_id' => Yii::$app->user->id
                ]);
            }
            $modelLikeForm = LikeForm::findOne([
                'document_id' => $document_id,
                'user_id' => Yii::$app->user->id
            ]);
            if (!$modelLikeForm) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->like = 1;
                $modelLikeForm->dislike = 0;
                $modelLikeForm->created_at = time();
                $modelLikeForm->document_id = $document_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->user_id = Yii::$app->user->id;
                $modelLikeForm->save();
            } else {
                LikeForm::deleteAll([
                    'document_id' => $document_id,
                    'user_id' => Yii::$app->user->id
                ]);
            }
        }

        // количество лайков
        $likes = (new \yii\db\Query())
            ->from('like')
            ->where([
                'like' => 1,
                'document_id' => $document_id,
            ])
            ->count();

        if (!$dislike) {
            return $this->renderAjax('@frontend/views/templates/rating/like', [
                'document_id' => $document_id,
                'likes' => $likes
            ]);
        }

        // количество дизлайков
        $dislikes = (new \yii\db\Query())
            ->from('like')
            ->where([
                'like' => 0,
                'document_id' => $document_id,
            ])
            ->count();

        return $this->renderAjax('@frontend/views/templates/rating/like_and_dislike', [
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

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            if ($like) {
                // удаляет dislike, если есть
                LikeForm::deleteAll([
                    'like' => 1,
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
            // удаляет like, если есть
            LikeForm::deleteAll([
                'like' => 1,
                'document_id' => $document_id,
                'ip' => Yii::$app->request->userIP,
                'user_agent' => Yii::$app->request->userAgent
            ]);
            // с одним IP обновляется раз в сутки
            $modelLikeForm = LikeForm::find()
                ->where([
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->one();
            if (!$modelLikeForm) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->like = 0;
                $modelLikeForm->dislike = 1;
                $modelLikeForm->created_at = time();
                $modelLikeForm->document_id = $document_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->save();
            } else {
                LikeForm::deleteAll([
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
        } else {
            if ($like) {
                // удаляет dislike, если есть
                LikeForm::deleteAll([
                    'like' => 1,
                    'document_id' => $document_id,
                    'user_id' => Yii::$app->user->id
                ]);
            }
            $modelLikeForm = LikeForm::find()
                ->where([
                    'document_id' => $document_id,
                    'user_id' => Yii::$app->user->id
                ])
                ->one();
            if (!$modelLikeForm) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->like = 0;
                $modelLikeForm->dislike = 1;
                $modelLikeForm->created_at = time();
                $modelLikeForm->document_id = $document_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->user_id = Yii::$app->user->id;
                $modelLikeForm->save();
            } else {
                LikeForm::deleteAll([
                    'document_id' => $document_id,
                    'user_id' => Yii::$app->user->id
                ]);
            }
        }

        // количество дизлайков
        $dislikes = (new \yii\db\Query())
            ->from('like')
            ->where([
                'like' => 0,
                'document_id' => $document_id,
            ])
            ->count();

        if (!$like) {
            return $this->renderAjax('@frontend/views/templates/rating/like', [
                'document_id' => $document_id,
                'dislikes' => $dislikes,
            ]);
        }

        // количество лайков
        $likes = (new \yii\db\Query())
            ->from('like')
            ->where([
                'like' => 1,
                'document_id' => $document_id,
            ])
            ->count();

        return $this->renderAjax('@frontend/views/templates/rating/like_and_dislike', [
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
            (!$star_cost && $star_cost < 10 || $star_cost > 50))
        {

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

        // записываем результат
        if (Yii::$app->user->isGuest) {
            // с одним IP обновляется раз в сутки
            $modelLikeForm = LikeForm::find()
                ->where([
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->one();
            if (!$modelLikeForm) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->stars = $value;
                $modelLikeForm->created_at = time();
                $modelLikeForm->document_id = $document_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->save();
            } else {
                $modelLikeForm->stars = $value;
                $modelLikeForm->save();
            }
        } else {
            $modelLikeForm = LikeForm::find()
                ->where([
                    'document_id' => $document_id,
                    'user_id' => Yii::$app->user->id
                ])
                ->one();
            if (!$modelLikeForm) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->stars = $value;
                $modelLikeForm->created_at = time();
                $modelLikeForm->document_id = $document_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->user_id = Yii::$app->user->id;
                $modelLikeForm->save();
            } else {
                $modelLikeForm->stars = $value;
                $modelLikeForm->save();
            }
        }

        // подсчет процентов
        $data = (new \yii\db\Query())
            ->from('like')
            ->where([
                'document_id' => $document_id,
            ])
            ->all();

        // количество проголосовавших
        $votes_number = count($data);

        $percent_count = 0;
        $i = 0;
        foreach ($data as $item) {
            $percent_count = $percent_count + $item['stars'];
            $i++;
        }
        if ($i == 0) {
            $i = 1;
        }
        $percent_count = $percent_count / $i;

        return $this->renderAjax('@frontend/views/templates/rating/percentage', [
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

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            // удаляет dislike, если есть
            LikeForm::deleteAll([
                'like' => 0,
                'comment_id' => $comment_id,
                'ip' => Yii::$app->request->userIP,
                'user_agent' => Yii::$app->request->userAgent
            ]);
            // с одним IP обновляется раз в сутки
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('like')
                ->where([
                    'comment_id' => $comment_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])->one();
            if (!$data) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->like = 1;
                $modelLikeForm->dislike = 0;
                $modelLikeForm->created_at = time();
                $modelLikeForm->comment_id = $comment_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->save();
            } else {
                LikeForm::deleteAll([
                    'comment_id' => $comment_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
        } else {
                // удаляет dislike, если есть
                LikeForm::deleteAll([
                    'like' => 0,
                    'comment_id' => $comment_id,
                    'user_id' => Yii::$app->user->id
                ]);
            $modelLikeForm = LikeForm::findOne([
                'comment_id' => $comment_id,
                'user_id' => Yii::$app->user->id
            ]);
            if (!$modelLikeForm) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->like = 1;
                $modelLikeForm->dislike = 0;
                $modelLikeForm->created_at = time();
                $modelLikeForm->comment_id = $comment_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->user_id = Yii::$app->user->id;
                $modelLikeForm->save();
            } else {
                LikeForm::deleteAll([
                    'comment_id' => $comment_id,
                    'user_id' => Yii::$app->user->id
                ]);
            }
        }

        // количество лайков
        $likes = (new \yii\db\Query())
            ->from('like')
            ->where([
                'like' => 1,
                'comment_id' => $comment_id,
            ])
            ->count();

        // количество дизлайков
        $dislikes = (new \yii\db\Query())
            ->from('like')
            ->where([
                'like' => 0,
                'comment_id' => $comment_id,
            ])
            ->count();

        return $this->renderAjax('@frontend/views/templates/rating/comment-rating', [
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

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            // удаляет dislike, если есть
            LikeForm::deleteAll([
                'like' => 1,
                'comment_id' => $comment_id,
                'ip' => Yii::$app->request->userIP,
                'user_agent' => Yii::$app->request->userAgent
            ]);
            // удаляет like, если есть
            LikeForm::deleteAll([
                'like' => 1,
                'comment_id' => $comment_id,
                'ip' => Yii::$app->request->userIP,
                'user_agent' => Yii::$app->request->userAgent
            ]);
            // с одним IP обновляется раз в сутки
            $modelLikeForm = LikeForm::find()
                ->where([
                    'comment_id' => $comment_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->one();
            if (!$modelLikeForm) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->like = 0;
                $modelLikeForm->dislike = 1;
                $modelLikeForm->created_at = time();
                $modelLikeForm->comment_id = $comment_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->save();
            } else {
                LikeForm::deleteAll([
                    'comment_id' => $comment_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ]);
            }
        } else {
            // удаляет dislike, если есть
            LikeForm::deleteAll([
                'like' => 1,
                'comment_id' => $comment_id,
                'user_id' => Yii::$app->user->id
            ]);
            $modelLikeForm = LikeForm::find()
                ->where([
                    'comment_id' => $comment_id,
                    'user_id' => Yii::$app->user->id
                ])
                ->one();
            if (!$modelLikeForm) {
                $modelLikeForm = new LikeForm();
                $modelLikeForm->like = 0;
                $modelLikeForm->dislike = 1;
                $modelLikeForm->created_at = time();
                $modelLikeForm->comment_id = $comment_id;
                $modelLikeForm->ip = Yii::$app->request->userIP;
                $modelLikeForm->user_agent = Yii::$app->request->userAgent;
                $modelLikeForm->user_id = Yii::$app->user->id;
                $modelLikeForm->save();
            } else {
                LikeForm::deleteAll([
                    'comment_id' => $comment_id,
                    'user_id' => Yii::$app->user->id
                ]);
            }
        }

        // количество дизлайков
        $dislikes = (new \yii\db\Query())
            ->from('like')
            ->where([
                'like' => 0,
                'comment_id' => $comment_id,
            ])
            ->count();

        // количество лайков
        $likes = (new \yii\db\Query())
            ->from('like')
            ->where([
                'like' => 1,
                'comment_id' => $comment_id,
            ])
            ->count();

        return $this->renderAjax('@frontend/views/templates/rating/comment-rating', [
            'comment_id' => $comment_id,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ]);
    }
}