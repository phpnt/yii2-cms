<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 31.08.2018
 * Time: 6:07
 */

namespace backend\modules\geo\controllers;

use common\models\forms\GeoCityForm;
use Yii;
use common\models\search\GeoCitySearch;
use yii\base\InlineAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class CityController extends Controller
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
     * Отображение городов
     * @return string
     */
    public function actionIndex()
    {
        $allGeoCitySearch = new GeoCitySearch();
        $dataProviderGeoCitySearch = $allGeoCitySearch->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'allGeoCitySearch' => $allGeoCitySearch,
            'dataProviderGeoCitySearch' => $dataProviderGeoCitySearch,
        ]);
    }
}