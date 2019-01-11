<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.01.2019
 * Time: 8:44
 */

namespace common\models\forms;

use Yii;

class OAuthForm extends UserForm
{
    public $first_name;
    public $last_name;
    public $gender;

    public $provider_id;
    public $provider_user_id;
    public $page;

    public function rules()
    {
        $items = UserForm::rules();
        $items[] = [['email', 'status', 'role'], 'required'];
        $items[] = [['first_name', 'last_name'], 'required', 'on' => 'personal-data'];
        $items[] = [['email', 'first_name', 'last_name', 'page', 'role'], 'string'];
        $items[] = [['gender', 'status', 'provider_id', 'provider_user_id'], 'integer'];

        return $items;
    }

    /**
     * @return bool
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        if ($insert) {
            $this->auth_key = Yii::$app->security->generateRandomString();
            $this->setPassword(Yii::$app->security->generateRandomString(8));
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }
}