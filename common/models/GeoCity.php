<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "geo_city".
 *
 * @property int $id_geo_city ID
 * @property string $name_ru Русское название
 * @property string $name_en Английское название
 * @property string $lat Широта
 * @property string $lon Долгота
 * @property string $okato ОКАТО
 * @property int $id_geo_region Регион
 *
 * @property GeoRegion $geoRegion
 * @property User[] $users
 */
class GeoCity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'geo_city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_en', 'lat', 'lon', 'id_geo_region'], 'required'],
            [['lat', 'lon'], 'number'],
            [['id_geo_region'], 'integer'],
            [['name_ru', 'name_en'], 'string', 'max' => 128],
            [['okato'], 'string', 'max' => 20],
            [['id_geo_region'], 'exist', 'skipOnError' => true, 'targetClass' => GeoRegion::className(), 'targetAttribute' => ['id_geo_region' => 'id_geo_region']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_geo_city' => Yii::t('app', 'ID'),
            'name_ru' => Yii::t('app', 'Русское название'),
            'name_en' => Yii::t('app', 'Английское название'),
            'lat' => Yii::t('app', 'Широта'),
            'lon' => Yii::t('app', 'Долгота'),
            'okato' => Yii::t('app', 'ОКАТО'),
            'id_geo_region' => Yii::t('app', 'Регион'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoRegion()
    {
        return $this->hasOne(GeoRegion::className(), ['id_geo_region' => 'id_geo_region']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id_geo_city' => 'id_geo_city']);
    }
}
