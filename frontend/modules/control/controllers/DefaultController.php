<?php

namespace frontend\modules\control\controllers;

use common\models\Constants;
use common\models\extend\UserExtend;
use common\models\forms\DocumentForm;
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
    public $alias_menu_item;    // алиас элемента главного меню
    public $alias_sidebar_item; // алиас элемента бокового меню
    public $alias_item;         // алиас элемента

    private $menu_item;     // элемент главного меню

    public function init()
    {
        parent::init();

        if (Yii::$app->request->get('alias_menu_item')) {
            $this->alias_menu_item = Yii::$app->request->get('alias_menu_item');
        } else {
            $this->alias_menu_item = 'main';
        }

        $this->menu_item = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => $this->alias_menu_item])
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
                        'roles' => Yii::$app->userAccess->getUserAccess($this->menu_item['access'])
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
     * Отображение элемента или списка, при нажатии на главное меню
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('@frontend/views/templates/control/index', [
            'alias_menu_item' => $this->alias_menu_item
        ]);
    }

    /**
     * Отображение списка, при нажатии на боковое меню
     * @return string
     */
    public function actionViewList($alias_sidebar_item)
    {
        return $this->render('@frontend/views/templates/control/view-list', [
            'alias_menu_item' => $this->alias_menu_item,
            'alias_sidebar_item' => $alias_sidebar_item,
        ]);
    }

    /**
     * Отображение элемента
     * @return string
     */
    public function actionView($alias_menu_item, $alias_sidebar_item = null, $alias_item)
    {
        $modelDocumentForm = DocumentForm::findOne([
            'alias' => $alias_item,
            'status' => Constants::STATUS_DOC_ACTIVE
        ]);

        return $this->render('@frontend/views/templates/control/view', [
            'alias_menu_item' => $alias_menu_item,
            'alias_sidebar_item' => $alias_sidebar_item,
            'modelDocumentForm' => $modelDocumentForm,
        ]);
    }
}
