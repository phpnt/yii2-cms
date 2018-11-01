<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "source_message".
 *
 * @property int $id ID
 * @property string $category Категория сообщения
 * @property string $message Сообщение
 * @property string $location Местонахождение
 * @property string $hash Хеш сообщения
 *
 * @property Message[] $messages
 */
class SourceMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'source_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message', 'location'], 'string'],
            [['category'], 'string', 'max' => 255],
            [['hash'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Категория сообщения'),
            'message' => Yii::t('app', 'Сообщение'),
            'location' => Yii::t('app', 'Местонахождение'),
            'hash' => Yii::t('app', 'Хеш сообщения'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id']);
    }
}
