<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 31.08.2018
 * Time: 5:31
 */

namespace common\models\extend;

use common\models\forms\GeoRegionForm;
use common\models\forms\UserForm;
use common\models\GeoCountry;
use yii\helpers\ArrayHelper;

/**
 * @property array $countresList
 *
 * @property GeoRegionForm[] $geoRegions
 * @property UserForm[] $users
 */
class GeoCountryExtend extends GeoCountry
{
    /**
     * Возвращает список стран
     * @return array
     */
    public function getCountresList()
    {
        /* @var $parent self */
        $manyDocumentExtend = self::find()
            ->orderBy(['name_ru' => SORT_ASC])
            ->all();

        return ArrayHelper::map($manyDocumentExtend, 'id_geo_country', 'name_ru');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeoRegions()
    {
        return $this->hasMany(GeoRegionForm::className(), ['id_geo_country' => 'id_geo_country']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(UserForm::className(), ['id_geo_country' => 'id_geo_country']);
    }
}