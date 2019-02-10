<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.01.2019
 * Time: 9:32
 */

namespace common\widgets\Comment;

use common\models\Constants;
use Yii;
use yii\base\Widget;

class Comment extends Widget
{
    public $document_id;

    public $access_answers = true;  // разрешены ответы на комментарии
    public $access_guests = false;  // разрешены не авторизованным пользователям

    public $label;
    public $message_denied;

    public function init()
    {
        parent::init();

        if (!$this->label) {
            $this->label = Yii::t('app', 'Комментарии:');
        }
        if (!$this->message_denied) {
            $this->message_denied = Yii::t('app', 'Комментарии могут оставлять только зарегистрированные пользователи.');
        }
    }

    public function run()
    {
        $comments = (new \yii\db\Query())
            ->select(['*'])
            ->from('comment')
            ->where([
                'document_id' => $this->document_id,
                'parent_id' => null,
            ])
            ->andWhere(['!=', 'status', Constants::STATUS_DOC_BLOCKED])
            ->all();

        return $this->render('@frontend/views/templates/control/blocks/comment/index', [
            'widget' => $this,
            'document_id' => $this->document_id,
            'comments' => $comments,
            'access_answers' => $this->access_answers
        ]);
    }
}