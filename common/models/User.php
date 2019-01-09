<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id ID
 * @property string $email Email
 * @property string $auth_key Ключ авторизации
 * @property string $password_hash Хеш пароля
 * @property string $password_reset_token Токен восстановления пароля
 * @property string $email_confirm_token Токен подтвердждения Email
 * @property int $status Статус
 * @property string $ip IP
 * @property int $created_at Время создания
 * @property int $updated_at Время изменения
 * @property int $login_at Авторизован
 * @property int $document_id Профиль пользователя
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property Basket[] $baskets
 * @property Document[] $documents
 * @property Document[] $documents0
 * @property Like[] $likes
 * @property Document $document
 * @property UserOauthKey[] $userOauthKeys
 * @property Visit[] $visits
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'login_at', 'document_id'], 'integer'],
            [['email'], 'string', 'max' => 100],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email_confirm_token'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 20],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'auth_key' => Yii::t('app', 'Ключ авторизации'),
            'password_hash' => Yii::t('app', 'Хеш пароля'),
            'password_reset_token' => Yii::t('app', 'Токен восстановления пароля'),
            'email_confirm_token' => Yii::t('app', 'Токен подтвердждения Email'),
            'status' => Yii::t('app', 'Статус'),
            'ip' => Yii::t('app', 'IP'),
            'created_at' => Yii::t('app', 'Время создания'),
            'updated_at' => Yii::t('app', 'Время изменения'),
            'login_at' => Yii::t('app', 'Авторизован'),
            'document_id' => Yii::t('app', 'Профиль пользователя'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaskets()
    {
        return $this->hasMany(Basket::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments0()
    {
        return $this->hasMany(Document::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Like::className(), ['user_id' => 'id']);
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
    public function getUserOauthKeys()
    {
        return $this->hasMany(UserOauthKey::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(Visit::className(), ['user_id' => 'id']);
    }
}
