<?php

namespace frontend\modules\control\controllers;

use common\models\Constants;
use common\models\extend\UserExtend;
use common\models\forms\VisitForm;
use Yii;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    // информация о текущей странице
    public $page;

    public function init()
    {
        parent::init();

        if (Yii::$app->request->get('alias')) {
            $alias = Yii::$app->request->get('alias');
        } else {
            $alias = 'main';
        }

        $this->page = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => $alias])
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
        if (!Yii::$app->user->isGuest) {
            /* @var $user UserExtend */
            $user = Yii::$app->user->identity;
            if ($user->status == Constants::STATUS_BLOCKED) {
                Yii::$app->user->logout();
                Yii::$app->session->set(
                    'message',
                    [
                        'type' => 'danger',
                        'icon' => 'glyphicon glyphicon-ban',
                        'message' => Yii::t('app', 'Ваш аккаунт заблокирован.'),
                    ]
                );
            }
        }


        if (Yii::$app->request->get('alias')) {
            $alias = Yii::$app->request->get('alias');
        } else {
            $alias = 'main';
        }

        try {
            parent::beforeAction($action);
        } catch (BadRequestHttpException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new ErrorException($e->getMessage());
        }

        $data = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => $alias,
                //'parent_id' => $this->page['id'],
            ])
            ->one();

        $document_id = $data['id'];

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
    public function actionIndex($alias = 'main')
    {
        $template = false;
        if ($this->page['template_id']) {
            $template = (new \yii\db\Query())
                ->select(['*'])
                ->from('template')
                ->where([
                    'id' => $this->page['template_id'],
                    //'status' => Constants::STATUS_DOC_ACTIVE
                ])
                ->one();
        }

        return $this->render('@frontend/views/templates/control/index', [
            'page' => $this->page,
            'template' => $template,
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionViewList($alias, $folder_alias)
    {
        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => $folder_alias,
                'status' => Constants::STATUS_DOC_ACTIVE
            ])
            ->one();

        $template = false;
        if ($parent['template_id']) {
            $template = (new \yii\db\Query())
                ->select(['*'])
                ->from('template')
                ->where([
                    'id' => $parent['template_id'],
                    //'status' => Constants::STATUS_DOC_ACTIVE
                ])
                ->one();
        }

        return $this->render('@frontend/views/templates/control/view-list', [
            'page' => $this->page,
            'template' => $template,
            'parent' => $parent,
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionView($alias, $item_alias)
    {
        $item = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => $item_alias,
                'status' => Constants::STATUS_DOC_ACTIVE
            ])
            ->one();

        $parent = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'id' => $item['parent_id'],
                'status' => Constants::STATUS_DOC_ACTIVE
            ])
            ->one();

        $template = false;
        if ($parent['template_id']) {
            $template = (new \yii\db\Query())
                ->select(['*'])
                ->from('template')
                ->where([
                    'id' => $parent['template_id'],
                    //'status' => Constants::STATUS_DOC_ACTIVE
                ])
                ->one();
        }

        return $this->render('@frontend/views/templates/control/view', [
            'page' => $this->page,
            'template' => $template,
            'parent' => $parent,
            'item' => $item
        ]);
    }
}
