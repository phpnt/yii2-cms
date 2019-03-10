<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 21:44
 */

namespace common\widgets\Rating;

use Yii;
use yii\base\Widget;

class Rating extends Widget
{
    public $like = true;        // кнопка "нравиться"
    public $dislike = false;    // кнопка "не нравиться"

    public $percentage = false; // процентный
    public $stars_number = 10;  // количество звезд
    private $star_cost;         // цена звезды в процентах

    public $document_id;

    public $access_guests = true;   // разрешены не авторизованным пользователям

    public function init()
    {
        parent::init();
        if ($this->stars_number < 2 || $this->stars_number > 10) {
            $this->stars_number = 10;
        }
        if ($this->percentage) {
            $this->star_cost = 100 / $this->stars_number;
        }
    }

    public function run()
    {
        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'rating',
            ])
            ->one();

        if (($this->access_guests && Yii::$app->user->isGuest) || !Yii::$app->user->isGuest) {
            if ($this->percentage) {
                // подсчет процентов
                $data = (new \yii\db\Query())
                    ->select(['document.*'])
                    ->from('document')
                    ->where([
                        'annotation' => 'stars',
                        'parent_id' => $parent['id'],
                        'item_id' => $this->document_id,
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

                return $this->render('@frontend/views/templates/control/blocks/rating/percentage', [
                    'document_id' => $this->document_id,
                    'percent_count' => $percent_count,
                    'stars_number' => $this->stars_number,
                    'star_cost' => $this->star_cost,
                    'votes_number' => $votes_number
                ]);
            } elseif ($this->like && !$this->dislike) {
                // количество лайков
                $likes = (new \yii\db\Query())
                    ->select(['document.*'])
                    ->from('document')
                    ->where([
                        'annotation' => 'like',
                        'parent_id' => $parent['id'],
                        'item_id' => $this->document_id,
                    ])
                    ->count();

                return $this->render('@frontend/views/templates/control/blocks/rating/like', [
                    'document_id' => $this->document_id,
                    'likes' => $likes
                ]);
            } elseif (!$this->like && $this->dislike) {
                // количество дизлайков
                $dislikes = (new \yii\db\Query())
                    ->from('like')
                    ->where([
                        'like' => 0,
                        'document_id' => $this->document_id,
                    ])
                    ->count();

                return $this->render('@frontend/views/templates/control/blocks/rating/dislike', [
                    'document_id' => $this->document_id,
                    'dislikes' => $dislikes,
                ]);
            } elseif ($this->like && $this->dislike) {
                $likes = (new \yii\db\Query())
                    ->select(['document.*'])
                    ->from('document')
                    ->where([
                        'annotation' => 'like',
                        'parent_id' => $parent['id'],
                        'item_id' => $this->document_id,
                    ])
                    ->count();

                $dislikes = (new \yii\db\Query())
                    ->select(['document.*'])
                    ->from('document')
                    ->where([
                        'annotation' => 'dislike',
                        'parent_id' => $parent['id'],
                        'item_id' => $this->document_id,
                    ])
                    ->count();

                return $this->render('@frontend/views/templates/control/blocks/rating/like_and_dislike', [
                    'document_id' => $this->document_id,
                    'likes' => $likes,
                    'dislikes' => $dislikes,
                ]);
            }
        }
    }
}