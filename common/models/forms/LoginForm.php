<?php

namespace common\models\forms;

use common\models\Constants;
use Yii;
use yii\base\Model;

/**
 * Форма авторизации
 * Class LoginForm
 * @package lowbase\user\models\forms
 */
class LoginForm extends Model
{
    public $email;              // Электронная почта
    public $password;           // Пароль
    public $rememberMe = true;  // Запомнить меня

    private $_user = false;

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            // И Email и пароль должны быть заполнены
            [['email', 'password'], 'required'],
            // Булево значение (галочка)
            ['rememberMe', 'boolean'],
            // Валидация пароля из метода "validatePassword"
            ['password', 'validatePassword'],
            // Электронная почта
            ['email', 'email'],
        ];
    }

    /**
     * Наименование полей формы
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Пароль'),
            'email' => Yii::t('app', 'Email'),
            'rememberMe' => Yii::t('app', 'Запомнить меня'),
        ];
    }

    /**
     * Проверка комбинации Email - Пароль
     * @param $attribute
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {

            $modelUserForm = $this->getUser();
            /* @var $modelUserForm \common\models\forms\UserForm */

            if (!$modelUserForm || !$modelUserForm->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Неправильная введен Email или Пароль.'));
            } elseif ($modelUserForm && $modelUserForm->status == Constants::STATUS_WAIT) {
                $this->addError('email', Yii::t('app', 'Аккаунт не подтвержден. Проверьте Email.'));
            } elseif ($modelUserForm && $modelUserForm->status == Constants::STATUS_BLOCKED) {
                $this->addError('email', Yii::t('app', 'Аккаунт заблокирован. Свяжитель с администратором.'));
            }
        }
    }

    /**
     * Авторизация
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Получение модели пользователя
     * @return null static
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = UserForm::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }
}
