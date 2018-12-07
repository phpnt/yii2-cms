<?php

namespace frontend\modules\basket\controllers;

use common\models\Constants;
use common\models\forms\VisitForm;
use Yii;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Default controller for the `basket` module
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
                'class' => AccessControl::className(),
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

        if ($alias = Yii::$app->request->get('alias')) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'alias' => $alias,
                    'parent_id' => $this->page['id'],
                ])
                ->one();
            $document_id = $data['id'];
        } else {
            $document_id = $this->page['id'];
        }

        // контроль посещений страниц
        if (Yii::$app->user->isGuest) {
            // с одним IP обновляется раз в сутки
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('visit')
                ->where([
                    'document_id' => $document_id,
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->andWhere(['>', 'created_at', time() - 24*60*60])
                ->one();
            if ($data == false) {
                $modelVisitForm = new VisitForm();
                $modelVisitForm->created_at = time();
                $modelVisitForm->document_id = $document_id;
                $modelVisitForm->ip = Yii::$app->request->userIP;
                $modelVisitForm->user_agent = Yii::$app->request->userAgent;
                $modelVisitForm->save();
            }
        } else {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('visit')
                ->where([
                    'document_id' => $document_id,
                    'user_id' => Yii::$app->user->id
                ])
                ->one();

            if (!$data) {
                $modelVisitForm = new VisitForm();
                $modelVisitForm->created_at = time();
                $modelVisitForm->document_id = $document_id;
                $modelVisitForm->ip = Yii::$app->request->userIP;
                $modelVisitForm->user_agent = Yii::$app->request->userAgent;
                $modelVisitForm->user_id = Yii::$app->user->id;
                $modelVisitForm->save();
            }
        }

        return true;
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $items = (new \yii\db\Query())
                ->select(['*'])
                ->from('basket')
                ->where([
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent
                ])
                ->all();
        } else {
            $items = (new \yii\db\Query())
                ->select(['*'])
                ->from('basket')
                ->where(['user_id' => Yii::$app->user->id])
                ->all();
        }

        $dataItems = [];
        if ($items) {
            foreach ($items as $item) {
                $data = (new \yii\db\Query())
                    ->select(['*'])
                    ->from('document')
                    ->where([
                        'id' => $item['document_id'],
                    ])
                    ->one();
                $data['quantity'] = $item['quantity'];
                $dataItems[] = $data;
            }
        }

        return $this->render('index', [
            'page' => $this->page,
            'items' => $dataItems,
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionView($alias)
    {
        $item = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => $alias,
                'status' => Constants::STATUS_DOC_ACTIVE
            ])
            ->one();

        if (Yii::$app->user->isGuest) {
            $itemBasket = (new \yii\db\Query())
                ->select(['*'])
                ->from('basket')
                ->where([
                    'ip' => Yii::$app->request->userIP,
                    'user_agent' => Yii::$app->request->userAgent,
                    'document_id' => $item['id']
                ])
                ->one();
        } else {
            $itemBasket = (new \yii\db\Query())
                ->select(['*'])
                ->from('basket')
                ->where([
                    'user_id' => Yii::$app->user->id,
                    'document_id' => $item['id']
                ])
                ->one();
        }

        $item['quantity'] = $itemBasket['quantity'];

        return $this->render('view', [
            'page' => $this->page,
            'item' => $item
        ]);
    }
}
