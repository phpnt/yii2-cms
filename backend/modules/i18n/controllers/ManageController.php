<?php

namespace backend\modules\i18n\controllers;

use common\models\forms\SourceMessageForm;
use Yii;
use common\models\search\SourceMessageSearch;
use yii\base\InlineAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `i18n` module
 */
class ManageController extends Controller
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

    public function actionIndex()
    {
        $searchModel = SourceMessageSearch::getInstance();
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->get());
        $dataProvider->query->orderBy(['id' => SORT_DESC]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Поиск новых сообщений
    */
    public function actionRescan()
    {
        $result = SourceMessageSearch::getInstance()->extract();
        Yii::$app->session->set(
            'message',
            [
                'type'      => 'info',
                'message'   => Yii::t('app', 'Новых сообщений:') . ' ' . (isset($result['new']) ? $result['new'] : 0).'<br>'.Yii::t('app', 'Удаленных сообщений:') . ' ' . (isset($result['deleted']) ? $result['deleted'] : 0)
            ]
        );
        return $this->redirect('index');
    }

    /*
     * Очистка кеша
     */
    public function actionClearCache()
    {
        Yii::$app->cache->flush();

        Yii::$app->session->set(
            'message',
            [
                'type'      => 'info',
                'message'   => Yii::t('app', 'Кеш успешно очищен.')
            ]
        );

        return $this->redirect('index');
    }

    public function actionSave($id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['/i18n/manage/index']);
        }

        $modelSourceMessageForm = SourceMessageForm::findOne($id);
        $saveTranslation = false;

        if($modelSourceMessageForm) {
            $saveTranslation = $modelSourceMessageForm->saveMessages(Yii::$app->request->post('Messages'));
        }

        if ($saveTranslation) {
            Yii::$app->cache->flush();
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Успешно'),
                ]
            );
        } else {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ban',
                    'message' => Yii::t('app', 'Ошибка'),
                ]
            );
        }

        return $this->render('_message-tabs', [
            'modelSourceMessageForm' => $modelSourceMessageForm,
            'key' => $id,
        ]);
    }
}
