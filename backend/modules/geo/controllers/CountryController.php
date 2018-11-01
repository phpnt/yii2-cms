<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 31.08.2018
 * Time: 6:02
 */

namespace backend\modules\geo\controllers;

use common\models\forms\GeoCountryForm;
use Yii;
use common\models\search\GeoCountrySearch;
use yii\base\InlineAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class CountryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            /* @var $action InlineAction */
                            if (!Yii::$app->user->can($action->controller->module->id . '/' . $action->controller->id . '/' . $action->id)) {
                                throw new ForbiddenHttpException(Yii::t('app', 'У вас нет доступа к этой странице'));
                            };
                            return true;
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Отображение стран
     * @return string
     */
    public function actionIndex()
    {
        $allGeoCountrySearch = new GeoCountrySearch();
        $dataProviderGeoCountry = $allGeoCountrySearch->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'allGeoCountrySearch' => $allGeoCountrySearch,
            'dataProviderGeoCountry' => $dataProviderGeoCountry,
        ]);
    }

    /**
     * TypeAHead для формы
     */
    public function actionGetCountry($query)
    {
        $manyGeoCountryForm = GeoCountryForm::find()
            ->where(['like', 'name_ru', $query])
            ->orderBy(['name_ru' => SORT_ASC])
            ->all();

        $result = [];
        $i = 0;
        foreach ($manyGeoCountryForm as $modelGeoCountryForm) {
            /* @var $modelGeoCountryForm GeoCountryForm */
            $result[$i]['id'] = $modelGeoCountryForm->id_geo_country;
            $result[$i]['name'] = $modelGeoCountryForm->name_ru;
            $i++;
        }

        return $this->asJson($result);
    }
}