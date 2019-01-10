<?php
/**
 * @package   yii2-user
 * @author    Yuri Shekhovtsov <shekhovtsovy@yandex.ru>
 * @copyright Copyright &copy; Yuri Shekhovtsov, lowbase.ru, 2015 - 2016
 * @version   1.0.0
 */

namespace common\models\forms;

use common\models\Constants;
use Yii;

/**
 * Форма регистрации на сайте
 * Class SignupForm
 * @package lowbase\user\models\forms
 */
class SignupForm extends UserForm
{
    public $password;   // Пароль

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['password', 'email'], 'required'],   // Обязательные поля
            ['email', 'unique', 'targetClass' => self::className(),
                'message' => Yii::t('app', 'Данный Email уже зарегистрирован.')],  // Электронная почта должна быть уникальна
            ['email', 'email'], // Электронная почта
            [['password'], 'string', 'min' => 4],   // Пароль минимум 4 символа
            [['status'], 'integer'],    // Целочисленные значения
            [['login_at'], 'safe'], // Безопасные аттрибуты
            [['email'], 'string', 'max' => 100],    // Строка (максимум 100 символов)
            [['auth_key'], 'string', 'max' => 32],  // Строка (максимум 32 символа)
            [['ip'], 'string', 'max' => 20],    // Строка (максимуму 20 символов)
            [['password_hash', 'password_reset_token', 'email_confirm_token'], 'string', 'max' => 255], // Строка (максимум 255 символов)
            ['status', 'in', 'range' => array_keys(self::getStatusArray())], // Статус должен быть из списка статусов
            ['status', 'default', 'value' => Constants::STATUS_WAIT],    // Статус после регистрации "Ожидает подтверждения"
            [['email', 'password'], 'filter', 'filter' => 'trim'],   // Обрезаем строки по краям
            [['password_reset_token', 'email_confirm_token', 'auth_key', 'password_hash', 'email', 'ip', 'login_at'], 'default', 'value' => null],   // По умолчанию значение = null
        ];
    }

    /**
     * Наименования дополнительных полей формы
     * @return array
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['password'] = Yii::t('app', 'Пароль');
        return $labels;
    }

    /**
     * Генерация ключа авторизации, токена подтверждения регистрации
     * и хеширование пароля перед сохранением
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->setPassword($this->password);
            $this->generateAuthKey();
            $this->generateEmailConfirmToken();
            return true;
        }
        return false;
    }

    /**
     * Отправка письма согласно шаблону "confirmEmail"
     * после регистрации
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $auth = Yii::$app->authManager;
        $role = $auth->getRole('user');
        $auth->assign($role, $this->id);

        $template = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'status' => Constants::STATUS_DOC_ACTIVE,
                'alias' => 'confirm-email',
            ])
            ->one();

        if ($template) {
            $data = [
                '{NAME_1}' => $this->email,
                '{URL_1}' => Yii::$app->urlManager->createAbsoluteUrl(['/signup/default/confirm', 'token' => $this->email_confirm_token]),
                '{DATE_1}' => Yii::$app->formatter->asDate(time()),
            ];

            $content = Yii::$app->emailTemplate->getTemplate(Yii::t('app', $template['content']), $data);

            return Yii::$app->mailer
                ->compose(
                    ['html' => 'template-email'],
                    ['template' => $content]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::t('app', '{name} робот', ['name' => Yii::$app->name])])
                ->setTo($this->email)
                ->setSubject(Yii::t('app', $template['name']) . ' ' . Yii::$app->name)
                ->send();
        } else {
            return Yii::$app->mailer->compose(
                ['html' => 'confirm-email'],
                ['modelUserForm' => $this])
                ->setFrom([Yii::$app->params['adminEmail']])
                ->setTo($this->email)
                ->setSubject(Yii::t('app', 'Подтверждение регистрации на сайте'))
                ->send();
        }
    }
}
