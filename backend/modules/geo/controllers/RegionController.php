<?php

namespace backend\modules\geo\controllers;

use common\models\forms\GeoRegionForm;
use Yii;
use common\models\search\GeoRegionSearch;
use yii\base\InlineAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Region controller for the `geo` module
 */
class RegionController extends Controller
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
     * Отображение регионов
     * @return string
     */
    public function actionIndex()
    {
        $allGeoRegionSearch = new GeoRegionSearch();
        $dataProviderGeoRegionSearch = $allGeoRegionSearch->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'allGeoRegionSearch' => $allGeoRegionSearch,
            'dataProviderGeoRegionSearch' => $dataProviderGeoRegionSearch,
        ]);
    }

    /**
     * TypeAHead для формы
     */
    public function actionGetRegion($query)
    {
        $manyGeoRegionForm = GeoRegionForm::find()
            ->where(['like', 'name_ru', $query])
            ->orderBy(['name_ru' => SORT_ASC])
            ->all();

        $result = [];
        $i = 0;
        foreach ($manyGeoRegionForm as $modelGeoRegionForm) {
            /* @var $modelGeoRegionForm GeoRegionForm */
            $result[$i]['id'] = $modelGeoRegionForm->id_geo_region;
            $result[$i]['name'] = $modelGeoRegionForm->name_ru;
            $i++;
        }

        return $this->asJson($result);
    }
}
