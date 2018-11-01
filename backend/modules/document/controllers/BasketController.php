<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 18:27
 */

namespace backend\modules\document\controllers;

use common\models\forms\BasketForm;
use common\models\search\BasketSearch;
use Yii;
use yii\base\InlineAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class BasketController extends Controller
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
     * Управление документами
     *
     * @return string
     */
    public function actionIndex()
    {
        $allBasketSearch = new BasketSearch();
        $allBasketSearch->scenario = 'show-all';
        $dataProviderBasketSearch = $allBasketSearch->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'allBasketSearch' => $allBasketSearch,
            'dataProviderBasketSearch' => $dataProviderBasketSearch,
        ]);
    }

    /**
     * Просмотр элемента корзины
     * @return string
     */
    public function actionViewBasket($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelBasketForm = BasketForm::findOne($id);

        return $this->renderAjax('view-basket', [
            'modelBasketForm' => $modelBasketForm,
        ]);
    }
}