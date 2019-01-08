<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\widgets\oAuth\models;

use yii\db\ActiveRecord;

/**
 * Ключи авторизации пользователей
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $provider_id
 * @property string $provider_user_id
 * @property string $page
 */
class UserOauthKey extends ActiveRecord
{
    /**
     * Название таблицы
     * @return string
     */
    public static function tableName()
    {
        return 'user_oauth_key';
    }

    /**
     * Поддерживаемые социальные сети
     * @return array
     */
    public static function getAvailableClients()
    {
        return [
            'vkontakte' => 1,
            'google'    => 2,
            'facebook'  => 3,
            'github'    => 4,
            'linkedin'  => 5,
            'yandex'    => 7,
            'twitter'   => 8
        ];
    }

    /**
     * Приставки для формирования
     * личных страниц пользователей в 
     * социальных сетях
     * @return array
     */
    public static function getSites()
    {
        return [
            1 => '//vk.com/id',
            2 => '//plus.google.com/',
            3 => '//wwww.facebook.com/',
            4 => '//github.com/',
            7 => '',
            8 => '//twitter.com/'
        ];
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'provider_id'], 'integer'],    // Целочисленные значения
            [['provider_user_id', 'page'], 'string', 'max' => 255], // Строки с максимальной длинной 255 символов
            [['user_id', 'provider_id', 'provider_user_id'], 'required'],   // Обязательные поля для заполнения
            [['page'], 'default', 'value' => null], // Значение по умолчанию = null
        ];
    }

    /**
     * Наименование полей аттрибутов модели
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'user_id' =>  \Yii::t('app', 'Пользователь'),
            'provider_id' => \Yii::t('app', 'Провайдер'),
            'provider_user_id' => \Yii::t('app', 'Ключ аутентификации'),
            'page' => \Yii::t('app', 'Ключ Страница')
        ];
    }

    /**
     * Пользователь, которому принадлежит ключ
     * @return \yii\db\ActiveQuery
     */
    public function getUser($modelUser)
    {
        return $modelUser::findOne($this->user_id);
    }

    /**
     * Возвращает количество активированных социальных сетей
     * @param $user_id - ID пользователя
     * @return int|string
     */
    public static function isOAuth($user_id)
    {
        return self::find()->where(['user_id' => $user_id])->count();
    }
 }
