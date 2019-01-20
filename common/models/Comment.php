<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id ID
 * @property string $text Комментарий
 * @property int $document_id Документ
 * @property string $ip IP
 * @property string $user_agent Данные браузера
 * @property int $user_id Пользователь
 * @property int $parent_id Ответ на коммментарий
 * @property int $status Проверен
 * @property int $created_at Время создания
 * @property int $updated_at Время изменения
 *
 * @property Comment $parent
 * @property Comment[] $comments
 * @property Document $document
 * @property User $user
 * @property Like[] $likes
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'document_id', 'ip'], 'required'],
            [['text', 'user_agent'], 'string'],
            [['document_id', 'user_id', 'parent_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['ip'], 'string', 'max' => 20],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Комментарий'),
            'document_id' => Yii::t('app', 'Документ'),
            'ip' => Yii::t('app', 'IP'),
            'user_agent' => Yii::t('app', 'Данные браузера'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'parent_id' => Yii::t('app', 'Ответ на коммментарий'),
            'status' => Yii::t('app', 'Проверен'),
            'created_at' => Yii::t('app', 'Время создания'),
            'updated_at' => Yii::t('app', 'Время изменения'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Comment::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Like::className(), ['comment_id' => 'id']);
    }
}
