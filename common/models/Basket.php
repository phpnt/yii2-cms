<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "basket".
 *
 * @property int $id ID
 * @property int $created_at Время создания
 * @property int $document_id Документ
 * @property int $quantity Количество
 * @property int $status Статус оплаты
 * @property string $ip IP
 * @property string $user_agent Данные браузера
 * @property int $user_id Пользователь
 *
 * @property Document $document
 * @property User $user
 */
class Basket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'basket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'document_id', 'quantity', 'status', 'user_id'], 'integer'],
            [['document_id', 'quantity', 'ip'], 'required'],
            [['user_agent'], 'string'],
            [['ip'], 'string', 'max' => 20],
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
            'created_at' => Yii::t('app', 'Время создания'),
            'document_id' => Yii::t('app', 'Документ'),
            'quantity' => Yii::t('app', 'Количество'),
            'status' => Yii::t('app', 'Статус оплаты'),
            'ip' => Yii::t('app', 'IP'),
            'user_agent' => Yii::t('app', 'Данные браузера'),
            'user_id' => Yii::t('app', 'Пользователь'),
        ];
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
