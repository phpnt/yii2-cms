<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 06.02.2019
 * Time: 9:40
 */

namespace common\widgets\Rating;

use common\models\Constants;
use Yii;
use yii\base\Widget;

class CommentRating extends Widget
{
    public $comment_id;
    public $access_guests = true;

    public function init()
    {
        parent::init();
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

        $likes = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'like',
                'parent_id' => $parent['id'],
                'item_id' => $this->comment_id,
            ])
            ->count();

        $dislikes = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'annotation' => 'dislike',
                'parent_id' => $parent['id'],
                'item_id' => $this->comment_id,
            ])
            ->count();

        return $this->render('@frontend/views/templates/control/blocks/rating/comment-rating', [
            'comment_id' => $this->comment_id,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ]);
    }
}