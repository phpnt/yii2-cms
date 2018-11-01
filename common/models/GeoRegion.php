<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "geo_region".
 *
 * @property int $id_geo_region ID
 * @property string $iso ISO
 * @property string $name_ru Русское название
 * @property string $name_en Английское название
 * @property string $timezone Временная зона
 * @property string $okato ОКАТО
 * @property int $id_geo_country Страна
 *
 * @property GeoCity[] $geoCities
 * @property GeoCountry $geoCountry
 */
class GeoRegion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'geo_region';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_en', 'timezone', 'id_geo_country'], 'required'],
            [['id_geo_country'], 'integer'],
            [['iso'], 'string', 'max' => 7],
            [['name_ru', 'name_en'], 'string', 'max' => 128],
            [['timezone'], 'string', 'max' => 30],
            [['okato'], 'string', 'max' => 4],
            [['id_geo_country'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCountry::className(), 'targetAttribute' => ['id_geo_country' => 'id_geo_country']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_geo_region' => Yii::t('app', 'ID'),
            'iso' => Yii::t('app', 'ISO'),
            'name_ru' => Yii::t('app', 'Русское название'),
            'name_en' => Yii::t('app', 'Английское название'),
            'timezone' => Yii::t('app', 'Временная зона'),
            'okato' => Yii::t('app', 'ОКАТО'),
            'id_geo_country' => Yii::t('app', 'Страна'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCities()
    {
        return $this->hasMany(GeoCity::className(), ['id_geo_region' => 'id_geo_region']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoCountry()
    {
        return $this->hasOne(GeoCountry::className(), ['id_geo_country' => 'id_geo_country']);
    }
}
