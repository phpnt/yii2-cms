<?php

namespace frontend\modules\geo\controllers;

use common\widgets\TemplateOfElement\forms\GeoTemplateForm;
use Yii;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Default controller for the `geo` module
 */
class DefaultController extends Controller
{
    // информация о текущей странице
    public $page;

    public function init()
    {
        parent::init();
        $this->page = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => $this->module->id])
            ->one();
    }

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
                        'roles' => Yii::$app->userAccess->getUserAccess($this->page['access'])
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws ErrorException
     */
    public function beforeAction($action)
    {
        try {
            parent::beforeAction($action);
        } catch (BadRequestHttpException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new ErrorException($e->getMessage());
        }

        return true;
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($lang = null)
    {
        if ($lang) {
            Yii::$app->language = $lang;
        }

        if (!Yii::$app->request->isPjax || !Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        $modelGeoTemplateForm = GeoTemplateForm::findOne(['mark' => 'geo']);

        if ($modelGeoTemplateForm->load(Yii::$app->request->post()) && $modelGeoTemplateForm->validate()) {
            return $this->redirect(Url::to(['/control/default/index', 'id_geo_city' => $modelGeoTemplateForm->id_geo_city]));
        }

        if ($modelGeoTemplateForm->errors) {
            return $this->renderAjax('@frontend/views/templates/geo/_geo-form', [
                'page' => $this->page,
                'modelGeoTemplateForm' => $modelGeoTemplateForm,
            ]);
        }

        return $this->renderAjax('@frontend/views/templates/geo/geo', [
            'page' => $this->page,
            'modelGeoTemplateForm' => $modelGeoTemplateForm,
        ]);
    }

}
