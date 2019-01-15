<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 07.01.2019
 * Time: 6:47
 */

namespace common\widgets\TemplateOfElement\controllers;

use Yii;
use common\models\forms\GeoCityForm;
use common\models\forms\GeoCountryForm;
use common\models\forms\GeoRegionForm;
use yii\web\Controller;

class GeoController extends Controller
{
    /**
     * TypeAHead для формы
     */
    public function actionGetCountry($query, $lang)
    {
        $manyGeoCountryForm = GeoCountryForm::find()
            ->where(['like', 'name_ru', $query])
            ->orWhere(['like', 'short_name', $query])
            ->orderBy(['name_ru' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($manyGeoCountryForm as $modelGeoCountryForm) {
            /* @var $modelGeoCountryForm GeoCountryForm */
            if ($lang == 'ru' || $lang == 'ru_RU') {
                $result[] = [
                    'id' => $modelGeoCountryForm->id_geo_country,
                    'name' => $modelGeoCountryForm->name_ru
                ];
            } else {
                $result[] = [
                    'id' => $modelGeoCountryForm->id_geo_country,
                    'name' => $modelGeoCountryForm->short_name
                ];
            }
        }

        return $this->asJson($result);
    }

    /**
     * TypeAHead для формы
     */
    public function actionGetRegion($query, $lang)
    {
        $session = Yii::$app->session;
        $id_geo_country = $session->get('id_geo_country');
        if (!$id_geo_country) {
            $cookiesRequest = Yii::$app->request->cookies;
            if (isset($cookiesRequest['id_geo_country'])) {
                $id_geo_country = $cookiesRequest['id_geo_country']->value;
            }
        }
        
        if (isset($id_geo_country) && $id_geo_country) {
            $manyGeoRegionForm = GeoRegionForm::find()
                ->where(['id_geo_country' => $id_geo_country])
                ->andWhere(['like', 'name_ru', $query])
                ->orderBy(['name_ru' => SORT_ASC])
                ->all();

            if (!$manyGeoRegionForm) {
                $manyGeoRegionForm = GeoRegionForm::find()
                    ->where(['id_geo_country' => $id_geo_country])
                    ->andWhere(['like', 'name_en', $query])
                    ->orderBy(['name_ru' => SORT_ASC])
                    ->all();
            }
        } else {
            $manyGeoRegionForm = GeoRegionForm::find()
                ->where(['like', 'name_ru', $query])
                ->orWhere(['like', 'name_en', $query])
                ->orderBy(['name_ru' => SORT_ASC])
                ->all();
        }

        $result = [];
        foreach ($manyGeoRegionForm as $modelGeoRegionForm) {
            /* @var $modelGeoRegionForm GeoRegionForm */
            if ($lang == 'ru' || $lang == 'ru_RU') {
                $result[] = [
                    'id' => $modelGeoRegionForm->id_geo_region,
                    'name' => $modelGeoRegionForm->name_ru
                ];
            } else {
                $result[] = [
                    'id' => $modelGeoRegionForm->id_geo_region,
                    'name' => $modelGeoRegionForm->name_en
                ];
            }
        }

        return $this->asJson($result);
    }

    /**
     * TypeAHead для формы
     */
    public function actionGetCity($query, $lang)
    {
        $session = Yii::$app->session;
        $cookiesRequest = Yii::$app->request->cookies;
        $id_geo_country = $session->get('id_geo_country');
        if (!$id_geo_country) {
            if (isset($cookiesRequest['id_geo_country'])) {
                $id_geo_country = $cookiesRequest['id_geo_country']->value;
            }
        }

        $id_geo_region = $session->get('id_geo_region');
        if (!$id_geo_region) {
            if (isset($cookiesRequest['id_geo_region'])) {
                $id_geo_region = $cookiesRequest['id_geo_region']->value;
            }
        }

        if (isset($id_geo_region) && $id_geo_region) {
            $manyGeoCityForm = GeoCityForm::find()
                ->where(['id_geo_region' => $id_geo_region])
                ->andWhere(['like', 'name_ru', $query])
                ->orderBy(['name_ru' => SORT_ASC])
                ->all();

            if (!$manyGeoCityForm) {
                $manyGeoCityForm = GeoCityForm::find()
                    ->where(['id_geo_region' => $id_geo_region])
                    ->andWhere(['like', 'name_en', $query])
                    ->orderBy(['name_ru' => SORT_ASC])
                    ->all();
            }
        } elseif (!isset($id_geo_region) && !$id_geo_region && isset($id_geo_country) && $id_geo_country) {
            // если не определен регион, но определена страна
            $regions = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_region')
                ->where([
                    'id_geo_country' => $id_geo_country
                ])
                ->all();

            $regionsID = [];
            if ($regions) {
                foreach ($regions as $region) {
                    $regionsID[] = $region['id_geo_region'];
                }

                $manyGeoCityForm = GeoCityForm::find()
                    ->where(['id_geo_region' => $regionsID])
                    ->andWhere(['like', 'name_ru', $query])
                    ->orderBy(['name_ru' => SORT_ASC])
                    ->all();

                if (!$manyGeoCityForm) {
                    $manyGeoCityForm = GeoCityForm::find()
                        ->where(['id_geo_region' => $regionsID])
                        ->andWhere(['like', 'name_en', $query])
                        ->orderBy(['name_ru' => SORT_ASC])
                        ->all();
                }
            } else {
                $manyGeoCityForm = GeoCityForm::find()
                    ->where(['like', 'name_ru', $query])
                    ->orWhere(['like', 'name_en', $query])
                    ->orderBy(['name_ru' => SORT_ASC])
                    ->all();
            }
        } else {
            $manyGeoCityForm = GeoCityForm::find()
                ->where(['like', 'name_ru', $query])
                ->orWhere(['like', 'name_en', $query])
                ->orderBy(['name_ru' => SORT_ASC])
                ->all();
        }

        $result = [];
        foreach ($manyGeoCityForm as $modelGeoCityForm) {
            /* @var $modelGeoCityForm GeoCityForm */
            if ($lang == 'ru' || $lang == 'ru_RU') {
                $result[] = [
                    'id' => $modelGeoCityForm->id_geo_city,
                    'name' => $modelGeoCityForm->name_ru
                ];
            } else {
                $result[] = [
                    'id' => $modelGeoCityForm->id_geo_city,
                    'name' => $modelGeoCityForm->name_en
                ];
            }
        }

        return $this->asJson($result);
    }

    /**
     * Установка кук
     */
    public function actionSetCookie($name, $value = null)
    {
        $session = Yii::$app->session;
        $cookiesResponse = Yii::$app->response->cookies;
        $cookiesRequest = Yii::$app->request->cookies;

        if (isset($cookiesRequest[$name])) {
            $session->remove($name);
            $cookiesResponse->remove($name);
        }

        if ($name == 'id_geo_country') {
            // если выбрана новая страна, удаляем из кук регион и город
            $session->remove('id_geo_region');
            if (isset($cookiesRequest['id_geo_region'])) {
                $cookiesResponse->remove('id_geo_region');
            }
            $session->remove('id_geo_city');
            if (isset($cookiesRequest['id_geo_city'])) {
                $cookiesResponse->remove('id_geo_city');
            }
        } elseif ($name == 'id_geo_region') {
            // если выбран новаый регион, удаляем из кук город
            $session->remove('id_geo_city');
            if (isset($cookiesRequest['id_geo_city'])) {
                $cookiesResponse->remove('id_geo_city');
            }
        }

        $session->set($name, $value);

        $cookiesResponse->add(new \yii\web\Cookie([
            'name' => $name,
            'value' => $value,
            'expire' => time() + (60 * 60 * 24 * 31),   // хранятся месяц
        ]));

        return $this->renderAjax('@common/widgets/TemplateOfElement/views/geo-fields/clean');
    }
}