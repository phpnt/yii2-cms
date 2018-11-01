<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id ID
 * @property string $first_name Имя
 * @property string $last_name Фамилия
 * @property string $auth_key Ключ авторизации
 * @property string $password_hash Хеш пароля
 * @property string $password_reset_token Токен восстановления пароля
 * @property string $email_confirm_token Токен подтвердждения Email
 * @property string $email Email
 * @property string $image Фото
 * @property int $sex Пол
 * @property int $birthday Дата рождения
 * @property string $phone Телефон
 * @property int $id_geo_country Страна
 * @property int $id_geo_city Город
 * @property string $address Адрес
 * @property int $status Статус
 * @property string $ip IP
 * @property int $created_at Время создания
 * @property int $updated_at Время изменения
 * @property int $login_at Авторизован
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property Basket[] $baskets
 * @property Document[] $documents
 * @property Document[] $documents0
 * @property Like[] $likes
 * @property GeoCity $geoCity
 * @property GeoCountry $geoCountry
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
            [['sex', 'birthday', 'id_geo_country', 'id_geo_city', 'status', 'created_at', 'updated_at', 'login_at'], 'integer'],
            [['first_name', 'last_name', 'email', 'phone'], 'string', 'max' => 100],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email_confirm_token', 'image', 'address'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 20],
            [['id_geo_city'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCity::className(), 'targetAttribute' => ['id_geo_city' => 'id_geo_city']],
            [['id_geo_country'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCountry::className(), 'targetAttribute' => ['id_geo_country' => 'id_geo_country']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'Имя'),
            'last_name' => Yii::t('app', 'Фамилия'),
            'auth_key' => Yii::t('app', 'Ключ авторизации'),
            'password_hash' => Yii::t('app', 'Хеш пароля'),
            'password_reset_token' => Yii::t('app', 'Токен восстановления пароля'),
            'email_confirm_token' => Yii::t('app', 'Токен подтвердждения Email'),
            'email' => Yii::t('app', 'Email'),
            'image' => Yii::t('app', 'Фото'),
            'sex' => Yii::t('app', 'Пол'),
            'birthday' => Yii::t('app', 'Дата рождения'),
            'phone' => Yii::t('app', 'Телефон'),
            'id_geo_country' => Yii::t('app', 'Страна'),
            'id_geo_city' => Yii::t('app', 'Город'),
            'address' => Yii::t('app', 'Адрес'),
            'status' => Yii::t('app', 'Статус'),
            'ip' => Yii::t('app', 'IP'),
            'created_at' => Yii::t('app', 'Время создания'),
            'updated_at' => Yii::t('app', 'Время изменения'),
            'login_at' => Yii::t('app', 'Авторизован'),
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
    public function getGeoCity()
    {
        return $this->hasOne(GeoCity::className(), ['id_geo_city' => 'id_geo_city']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCountry()
    {
        return $this->hasOne(GeoCountry::className(), ['id_geo_country' => 'id_geo_country']);
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
