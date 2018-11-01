<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_oauth_key".
 *
 * @property int $id ID
 * @property int $user_id Пользователь
 * @property int $provider_id Провайдер
 * @property string $provider_user_id Прользователь провайдера
 * @property string $page Страница
 *
 * @property User $user
 */
class UserOauthKey extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_oauth_key';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'provider_id', 'provider_user_id'], 'required'],
            [['user_id', 'provider_id'], 'integer'],
            [['provider_user_id', 'page'], 'string', 'max' => 255],
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
            'user_id' => Yii::t('app', 'Пользователь'),
            'provider_id' => Yii::t('app', 'Провайдер'),
            'provider_user_id' => Yii::t('app', 'Прользователь провайдера'),
            'page' => Yii::t('app', 'Страница'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
