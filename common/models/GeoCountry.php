<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "geo_country".
 *
 * @property int $id_geo_country ID
 * @property string $continent Континент
 * @property string $name_ru Русское название
 * @property string $lat Широта
 * @property string $lon Долгота
 * @property string $timezone Временная зона
 * @property string $iso2 ISO2
 * @property string $short_name Короткое название
 * @property string $long_name Длинное название
 * @property string $iso3 ISO3
 * @property string $num_code Цифровой код
 * @property string $un_member Участник
 * @property string $calling_code Телефонный код
 * @property string $cctld Доменная зона
 * @property int $phone_number_digits Количество цифр в телефонном номере
 * @property string $currency Валюта
 * @property int $system_measure Система измерения
 * @property int $active Активный
 *
 * @property GeoRegion[] $geoRegions
 * @property User[] $users
 */
class GeoCountry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'geo_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['continent', 'name_ru', 'lat', 'lon', 'timezone', 'iso2', 'short_name', 'long_name', 'iso3', 'num_code', 'un_member', 'calling_code', 'cctld', 'currency'], 'required'],
            [['lat', 'lon'], 'number'],
            [['phone_number_digits', 'system_measure', 'active'], 'integer'],
            [['continent', 'iso2'], 'string', 'max' => 2],
            [['name_ru'], 'string', 'max' => 128],
            [['timezone'], 'string', 'max' => 30],
            [['short_name', 'long_name'], 'string', 'max' => 80],
            [['iso3', 'currency'], 'string', 'max' => 3],
            [['num_code'], 'string', 'max' => 6],
            [['un_member'], 'string', 'max' => 12],
            [['calling_code'], 'string', 'max' => 8],
            [['cctld'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_geo_country' => Yii::t('app', 'ID'),
            'continent' => Yii::t('app', 'Континент'),
            'name_ru' => Yii::t('app', 'Русское название'),
            'lat' => Yii::t('app', 'Широта'),
            'lon' => Yii::t('app', 'Долгота'),
            'timezone' => Yii::t('app', 'Временная зона'),
            'iso2' => Yii::t('app', 'ISO2'),
            'short_name' => Yii::t('app', 'Короткое название'),
            'long_name' => Yii::t('app', 'Длинное название'),
            'iso3' => Yii::t('app', 'ISO3'),
            'num_code' => Yii::t('app', 'Цифровой код'),
            'un_member' => Yii::t('app', 'Участник'),
            'calling_code' => Yii::t('app', 'Телефонный код'),
            'cctld' => Yii::t('app', 'Доменная зона'),
            'phone_number_digits' => Yii::t('app', 'Количество цифр в телефонном номере'),
            'currency' => Yii::t('app', 'Валюта'),
            'system_measure' => Yii::t('app', 'Система измерения'),
            'active' => Yii::t('app', 'Активный'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoRegions()
    {
        return $this->hasMany(GeoRegion::className(), ['id_geo_country' => 'id_geo_country']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id_geo_country' => 'id_geo_country']);
    }
}
