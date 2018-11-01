<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.08.2018
 * Time: 10:54
 */

namespace common\models\forms;

use common\models\Constants;
use Yii;
use yii\base\Model;

/**
 * Форма сброса пароля
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\forms\UserForm',
                'filter' => ['status' => Constants::STATUS_ACTIVE],
                'message' => Yii::t('app', 'Пользователь с введем адресом электронной почты не зарегистрирован')
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Электронная почта'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $modelUserForm UserForm */
        $modelUserForm = UserForm::findOne([
            'status' => Constants::STATUS_ACTIVE,
            'email' => $this->email,
        ]);
        if (!$modelUserForm) {
            return false;
        }

        if (!UserForm::isPasswordResetTokenValid($modelUserForm->password_reset_token)) {
            $modelUserForm->generatePasswordResetToken();
            if (!$modelUserForm->save()) {
                return false;
            }
        }

        $template = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'status' => Constants::STATUS_DOC_ACTIVE,
                'alias' => 'password-reset-token',
            ])
            ->one();

        if ($template) {
            $data = [
                '{EMAIL_1}' => $modelUserForm->email,
                '{URL_1}' => Yii::$app->urlManager->createAbsoluteUrl(['/login/default/reset-password', 'token' => $modelUserForm->password_reset_token]),
                '{DATE_1}' => Yii::$app->formatter->asDate(time()),
            ];

            $template = Yii::$app->emailTemplate->getTemplate(Yii::t('app', $template['content']), $data);

            return Yii::$app->mailer
                ->compose(
                    ['html' => 'template-email'],
                    ['template' => $template]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::t('app', '{name} робот', ['name' => Yii::$app->name])])
                ->setTo($this->email)
                ->setSubject(Yii::t('app', 'Сброс пароля для {name}', ['name' => Yii::$app->name]))
                ->send();
        } else {
            return Yii::$app->mailer
                ->compose(
                    ['html' => 'password-reset-token'],
                    ['modelUserForm' => $modelUserForm]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::t('app', '{name} робот', ['name' => Yii::$app->name])])
                ->setTo($this->email)
                ->setSubject(Yii::t('app', 'Сброс пароля для {name}', ['name' => Yii::$app->name]))
                ->send();
        }
    }
}