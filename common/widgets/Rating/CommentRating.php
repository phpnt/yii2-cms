<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 06.02.2019
 * Time: 9:40
 */

namespace common\widgets\Rating;

use Yii;
use yii\base\Widget;

class CommentRating extends Widget
{
    public $comment_id;
    public $access_guests = true;   // разрешены не авторизованным пользовател€м

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        // количество лайков
        $likes = (new \yii\db\Query())
            ->from('like')
            ->where([
                'like' => 1,
                'comment_id' => $this->comment_id,
            ])
            ->count();
        // количество дизлайков
        $dislikes = (new \yii\db\Query())
            ->from('like')
            ->where([
                'like' => 0,
                'comment_id' => $this->comment_id,
            ])
            ->count();

        return $this->render('@frontend/views/templates/rating/comment-rating', [
            'comment_id' => $this->comment_id,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ]);
    }
}