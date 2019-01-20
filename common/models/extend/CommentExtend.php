<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 18.01.2019
 * Time: 16:18
 */

namespace common\models\extend;

use Yii;
use common\models\Comment;
use common\models\Constants;
use common\models\forms\CommentForm;
use common\models\forms\DocumentForm;
use common\models\forms\LikeForm;
use common\models\forms\UserForm;

/**
 * @property array $statusList
 * @property array $statusItem
 *
 * @property CommentForm $parent
 * @property CommentForm[] $comments
 * @property DocumentForm $document
 * @property UserForm $user
 * @property LikeForm[] $likes
 */
class CommentExtend extends Comment
{
    /**
     * Возвращает массив статусов документа
     * @return array
     */
    public function getStatusList()
    {
        return [
            null => Yii::t('app', '---'),
            Constants::STATUS_DOC_WAIT =>  Yii::t('app', 'Не подтвержден'),
            Constants::STATUS_DOC_ACTIVE => Yii::t('app', 'Опубликован'),
            Constants::STATUS_DOC_BLOCKED => Yii::t('app', 'Заблокирован'),
        ];
    }

    /**
     * Возвращает статус документа
     */
    public function getStatusItem()
    {
        switch ($this->status) {
            case Constants::STATUS_DOC_WAIT:
                return '<i class="fas fa-hourglass-half"></i>';
                break;
            case Constants::STATUS_DOC_ACTIVE:
                return '<i class="fas fa-check"></i>';
                break;
            case Constants::STATUS_DOC_BLOCKED:
                return '<i class="fas fa-ban"></i>';
                break;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(CommentForm::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(CommentForm::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(DocumentForm::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserForm::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(LikeForm::className(), ['comment_id' => 'id']);
    }
}
