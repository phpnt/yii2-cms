<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:29
 */

namespace common\models\forms;

use common\models\Constants;
use Yii;
use common\models\extend\UserExtend;
use yii\behaviors\TimestampBehavior;

class UserForm extends UserExtend
{
    public $photo;  // аватар (само изображение)
    public $password;
    public $password_confirm;

    public $role;
    public $permission;

    public function rules()
    {
        $items = UserExtend::rules();
        $items[] = [['email', 'status', 'role'], 'required'];
        $items[] = [['photo'], 'image', 'minHeight' => 100, 'skipOnEmpty' => true];
        $items[] = [['password', 'password_confirm'], 'required', 'on' => 'create-user'];
        $items[] = [['password', 'password_confirm'], 'string'];
        $items[] = ['password_confirm', 'compare', 'compareAttribute' => 'password'];

        return $items;
    }

    public function attributeLabels()
    {
        $items = UserExtend::attributeLabels();
        $items['photo'] = Yii::t('app', 'Фото');
        $items['password'] = Yii::t('app', 'Пароль');
        $items['password_confirm'] = Yii::t('app', 'Подтверждение пароля');
        $items['role'] = Yii::t('app', 'Роль');

        return $items;
    }

    /**
     * Автозаполнение полей создание и редактирование
     * профиля
     * @return array
     */
    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value' => time(),
        ]];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        parent::beforeValidate();

        if ($this->password) {
            $this->setPassword($this->password);
        }
        if ($this->scenario == 'create-user') {
            $this->auth_key = Yii::$app->security->generateRandomString();
        }

        return true;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        /* Назначение роли */
        if ($this->role) {
            AuthAssignmentForm::deleteAll(['user_id' => $this->id]);
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($this->role);
            $auth->assign($role, $this->id);
        }
    }

    public function afterFind()
    {
        parent::afterFind();

        foreach ($this->authAssignments as $authAssignment) {
            $this->role = $authAssignment->item_name;
        }
    }
}