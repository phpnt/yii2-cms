<?php

namespace frontend\modules\profile\controllers;

use common\models\Constants;
use common\models\forms\DocumentForm;
use common\models\forms\UserForm;
use common\models\forms\ValueFileForm;
use common\models\forms\VisitForm;
use common\widgets\TemplateOfElement\forms\ProfileTemplateForm;
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

        return $this->render('index', [
            'page' => $this->page,
            'modelProfileTemplateForm' => $modelProfileTemplateForm,
            'manyProfiles' => $manyProfiles
        ]);
    }

    /**
     * Выбор формы профиля
     *
     * @return mixed
     */
    public function actionSelectProfile()
    {
        /* @var $modelUserForm UserForm */
        $modelUserForm = Yii::$app->user->identity;

        $modelProfileTemplateForm = new ProfileTemplateForm();
        $modelProfileTemplateForm->name = $modelUserForm->email;
        $modelProfileTemplateForm->alias = $modelUserForm->email;
        $modelProfileTemplateForm->status = Constants::STATUS_ACTIVE;
        $modelProfileTemplateForm->created_by = $modelUserForm->id;
        $modelProfileTemplateForm->updated_by = $modelUserForm->id;

        if (Yii::$app->request->post('profile')) {
            // извлекаем профиль
            $profile = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'id' => Yii::$app->request->post('profile')
                ])
                ->one();
        } else {
            $modelProfileTemplateForm->load(Yii::$app->request->post());
            // извлекаем профиль
            $profile = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'id' => $modelProfileTemplateForm->parent_id
                ])
                ->one();
        }

        $modelProfileTemplateForm->parent_id = $profile['id'];
        $modelProfileTemplateForm->template_id = $profile['template_id'];

        return $this->renderAjax('@frontend/views/templates/profile/profile', [
            'page' => $this->page,
            'profile' => $profile,
            'modelProfileTemplateForm' => $modelProfileTemplateForm,
        ]);
    }

    /**
     * Выбор формы профиля
     *
     * @return mixed
     * @throws ErrorException
     */
    public function actionSaveProfile()
    {
        /* @var $modelUserForm UserForm */
        $modelUserForm = Yii::$app->user->identity;

        $modelProfileTemplateForm = new ProfileTemplateForm();
        $modelProfileTemplateForm->load(Yii::$app->request->post());

        // если сменился профиль, удаляем старый
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

        if ($modelProfileTemplateForm->save()) {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Успешно'),
                ]
            );
            return $this->redirect('index');
        }

        return $this->renderAjax('@frontend/views/templates/profile/_form-profile', [
            'page' => $this->page,
            'modelProfileTemplateForm' => $modelProfileTemplateForm,
        ]);
    }

    /**
     * Изменение документа
     * @return string
     */
    public function actionUpdateProfile($id_document, $id_folder)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect(['index']);
        }

        $modelProfileTemplateForm = ProfileTemplateForm::findOne($id_document);
        $modelProfileTemplateForm->parent_id = $id_folder;

        if ($modelProfileTemplateForm->load(Yii::$app->request->post()) && $modelProfileTemplateForm->save()) {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Успешно'),
                ]
            );
            return $this->redirect('index');
        }

        if ($modelProfileTemplateForm->errors) {
            return $this->renderAjax('@frontend/views/templates/profile/_form-profile', [
                'page' => $this->page,
                'modelProfileTemplateForm' => $modelProfileTemplateForm,
            ]);
        }

        // извлекаем профиль
        $profile = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'id' => $modelProfileTemplateForm->parent_id
            ])
            ->one();

        return $this->renderAjax('@frontend/views/templates/profile/profile', [
            'page' => $this->page,
            'profile' => $profile,
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
