<?php

namespace backend\modules\settings\controllers;

use common\models\search\LikeSearch;
use common\models\search\VisitSearch;
use Yii;
use yii\base\InlineAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `settings` module
 */
class StatisticController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
     * Статистика
     * @return string
     */
    public function actionIndex()
    {
        $allVisitSearch = new VisitSearch();
        $dataProviderVisitSearch = $allVisitSearch->search(Yii::$app->request->queryParams);

        $allLikeSearch = new LikeSearch();
        $dataProviderLikeSearch = $allLikeSearch->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'allVisitSearch' => $allVisitSearch,
            'dataProviderVisitSearch' => $dataProviderVisitSearch,
            'allLikeSearch' => $allLikeSearch,
            'dataProviderLikeSearch' => $dataProviderLikeSearch,
        ]);
    }
}
