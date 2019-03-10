<?php

namespace frontend\modules\profile\controllers;

use common\models\Constants;
use common\models\forms\DocumentForm;
use common\models\forms\UserForm;
use common\models\forms\ValueFileForm;
use common\widgets\TemplateOfElement\forms\ProfileTemplateForm;
use yii\helpers\Url;
use Yii;
use yii\base\ErrorException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Default controller for the `profile` module
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }

        /* @var $modelUserForm UserForm */
        $modelUserForm = Yii::$app->user->identity;

        if ($modelUserForm->document_id) {
            $modelProfileTemplateForm = ProfileTemplateForm::findOne(['id' => $modelUserForm->document_id]);
        } else {
            $modelProfileTemplateForm = new ProfileTemplateForm();
        }

        // извлекаем возможные профили
        $manyProfiles = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'status' => Constants::STATUS_DOC_ACTIVE,
                'parent_id' => $this->page['id']
            ])
            ->orderBy(['position' => SORT_ASC])
            ->all();

        return $this->render('@frontend/views/templates/profile/index', [
            'page' => $this->page,
            'modelProfileTemplateForm' => $modelProfileTemplateForm,
            'manyProfiles' => $manyProfiles
        ]);
    }

    /**
     * Обновление профиля Pjax
     * @return string
     */
    public function actionRefreshProfile()
    {
        if (Yii::$app->user->isGuest && !Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        /* @var $modelUserForm UserForm */
        $modelUserForm = Yii::$app->user->identity;

        if ($modelUserForm->document_id) {
            $modelProfileTemplateForm = ProfileTemplateForm::findOne(['id' => $modelUserForm->document_id]);
        } else {
            $modelProfileTemplateForm = new ProfileTemplateForm();
        }

        // извлекаем возможные профили
        $manyProfiles = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'status' => Constants::STATUS_DOC_ACTIVE,
                'parent_id' => $this->page['id']
            ])
            ->orderBy(['position' => SORT_ASC])
            ->all();

        return $this->renderAjax('@frontend/views/templates/profile/index', [
            'page' => $this->page,
            'modelProfileTemplateForm' => $modelProfileTemplateForm,
            'manyProfiles' => $manyProfiles
        ]);
    }

    /**
     * Выбор формы профиля
     *
     * @return mixed
     * @throws ErrorException
     */
    public function actionCreateProfile($url = null, $container = null)
    {
        /* @var $modelUserForm UserForm */
        $modelUserForm = Yii::$app->user->identity;

        $modelProfileTemplateForm = new ProfileTemplateForm();
        $modelProfileTemplateForm->name = $modelUserForm->email;
        $modelProfileTemplateForm->alias = $modelUserForm->email;
        $modelProfileTemplateForm->status = Constants::STATUS_ACTIVE;
        $modelProfileTemplateForm->created_by = $modelUserForm->id;
        $modelProfileTemplateForm->updated_by = $modelUserForm->id;

        if ($url && $container) {
            $modelProfileTemplateForm->url = $url;
            $modelProfileTemplateForm->container = $container;
        }

        if ($modelProfileTemplateForm->load(Yii::$app->request->post())) {
            $profile = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'id' => $modelProfileTemplateForm->parent_id
                ])
                ->one();
            $modelProfileTemplateForm->template_id = $profile['template_id'];

            $modelDocumentForm = DocumentForm::findOne(['name' => $modelUserForm->email]);
            if ($modelDocumentForm) {
                $modelUserForm->document_id = null;
                $modelUserForm->save();
                try {
                    $modelDocumentForm->delete();
                } catch (StaleObjectException $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                } catch (\Throwable $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                }
            }

            if ($modelProfileTemplateForm->elements_fields && $modelProfileTemplateForm->save()) {
                Yii::$app->session->set(
                    'message',
                    [
                        'type' => 'success',
                        'icon' => 'glyphicon glyphicon-ok',
                        'message' => Yii::t('app', 'Успешно'),
                    ]
                );
                if (!$modelProfileTemplateForm->url || !$modelProfileTemplateForm->container) {
                    return $this->asJson([
                        'success' => 1,
                        'url' => Url::to(['refresh-profile']),
                        'container' => '#block-profile',
                    ]);
                }
                return $this->asJson([
                    'success' => 1,
                    'url' => $modelProfileTemplateForm->url,
                    'container' => $modelProfileTemplateForm->container,
                ]);
            }

            return $this->renderAjax('@frontend/views/templates/profile/_form-profile', [
                'page' => $this->page,
                'modelProfileTemplateForm' => $modelProfileTemplateForm,
                'modelUserForm' => $modelUserForm
            ]);
        }

        return $this->renderAjax('@frontend/views/templates/profile/profile', [
            'page' => $this->page,
            'modelProfileTemplateForm' => $modelProfileTemplateForm,
            'modelUserForm' => $modelUserForm,
        ]);
    }

    /**
     * Изменение документа
     *
     * @return string
     * @throws ErrorException
     */
    public function actionUpdateProfile($id_document)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        /* @var $modelUserForm UserForm */
        $modelUserForm = Yii::$app->user->identity;

        $modelProfileTemplateForm = ProfileTemplateForm::findOne($id_document);

        if ($modelProfileTemplateForm->load(Yii::$app->request->post())) {
            $profile = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'id' => $modelProfileTemplateForm->parent_id
                ])
                ->one();

            if ($modelProfileTemplateForm->template_id != $profile['template_id']) {
                $modelProfileTemplateForm->template_id = $profile['template_id'];
                $modelProfileTemplateForm->elements_fields = [];
            }

            if ($modelProfileTemplateForm->elements_fields && $modelProfileTemplateForm->save()) {
                Yii::$app->session->set(
                    'message',
                    [
                        'type' => 'success',
                        'icon' => 'glyphicon glyphicon-ok',
                        'message' => Yii::t('app', 'Успешно'),
                    ]
                );
                return $this->asJson([
                    'success' => 1,
                    'url' => Url::to(['refresh-profile']),
                    'container' => '#block-profile',
                ]);
            }

            return $this->renderAjax('@frontend/views/templates/profile/_form-profile', [
                'page' => $this->page,
                'modelProfileTemplateForm' => $modelProfileTemplateForm,
                'modelUserForm' => $modelUserForm
            ]);
        }

        if ($modelProfileTemplateForm->errors) {
            return $this->renderAjax('@frontend/views/templates/profile/_form-profile', [
                'page' => $this->page,
                'modelProfileTemplateForm' => $modelProfileTemplateForm,
            ]);
        }

        return $this->renderAjax('@frontend/views/templates/profile/profile', [
            'page' => $this->page,
            'modelProfileTemplateForm' => $modelProfileTemplateForm,
        ]);
    }

    /**
     * Подтверждение удаления документа
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteFile($id)
    {
        $modelValueFileForm = ValueFileForm::findOne($id);

        try {
            $modelValueFileForm->delete();
        } catch (StaleObjectException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new ErrorException($e->getMessage());
        } catch (\Throwable $e) {
            Yii::$app->errorHandler->logException($e);
            throw new ErrorException($e->getMessage());
        }

        Yii::$app->session->set(
            'message',
            [
                'type' => 'success',
                'icon' => 'glyphicon glyphicon-ok',
                'message' => Yii::t('app', 'Успешно'),
            ]
        );

        if ($modelValueFileForm->type == Constants::FIELD_TYPE_FILE) {
            return $this->renderAjax('@common/widgets/TemplateOfElement/views/default-fields/__file', ['modelValueFileForm' => null]);
        }

        $manyValueFileForm = ValueFileForm::findAll([
            'field_id' => $modelValueFileForm->field_id,
            'document_id' => $modelValueFileForm->document_id,
        ]);

        return $this->renderAjax('@common/widgets/TemplateOfElement/views/default-fields/__files', ['manyValueFileForm' => $manyValueFileForm]);
    }

    /**
     * Выход пользователя
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
