<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "like".
 *
 * @property int $id ID
 * @property int $like Нравиться
 * @property int $dislike Не нравиться
 * @property int $stars Количество звезд
 * @property int $created_at Время создания
 * @property int $document_id Документ
 * @property int $comment_id Комментарий
 * @property string $ip IP
 * @property string $user_agent Данные браузера
 * @property int $user_id Пользователь
 *
 * @property Comment $comment
 * @property Document $document
 * @property User $user
 */
class Like extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'like';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['like', 'dislike', 'stars', 'created_at', 'document_id', 'comment_id', 'user_id'], 'integer'],
            [['ip'], 'required'],
            [['user_agent'], 'string'],
            [['ip'], 'string', 'max' => 20],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['comment_id' => 'id']],
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
            'like' => Yii::t('app', 'Нравиться'),
            'dislike' => Yii::t('app', 'Не нравиться'),
            'stars' => Yii::t('app', 'Количество звезд'),
            'created_at' => Yii::t('app', 'Время создания'),
            'document_id' => Yii::t('app', 'Документ'),
            'comment_id' => Yii::t('app', 'Комментарий'),
            'ip' => Yii::t('app', 'IP'),
            'user_agent' => Yii::t('app', 'Данные браузера'),
            'user_id' => Yii::t('app', 'Пользователь'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comment::className(), ['id' => 'comment_id']);
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
}
