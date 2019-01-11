<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\widgets\oAuth\oauth;

use common\models\Constants;
use common\models\forms\UserOauthKeyForm;

/**
 * Авторизация с помощью Вконтакте
 * Class VKontakte
 */
class VKontakte extends \yii\authclient\clients\VKontakte
{
    public $female      = 1;
    public $male        = 2;

    public function init()
    {
        parent::init();
    }

    /**
     * Размеры Popap-окна
     * @return array
     */
    public function getViewOptions()
    {
        return [
            'popupWidth' => 900,
            'popupHeight' => 500
        ];
    }

    /**
     * Преобразование пола
     * @return array
     */
    public function normalizeSex()
    {
        return [
            '1' => $this->female,
            '2' => $this->male
        ];
    }

    /**
     * Получение аттрибутов
     * @return array
     * @throws \yii\base\Exception
     */
    protected function initUserAttributes()
    {
        $attributes = $this->api('users.get.json', 'GET', [
            'fields' => implode(',', [
                'uid',
                'first_name',
                'last_name',
                'bdate',
                'personal',
                'country',
                'sex'
            ]),
        ]);

        $attributes = array_shift($attributes['response']);

        $return_attributes = [
            'OAuthForm' => [
                'email'         => isset($attributes['email']) ? $attributes['email'] : $attributes['id'] . '_' . UserOauthKeyForm::getAvailableClients()['vkontakte'],
                'first_name'    => $attributes['first_name'],
                'last_name'     => $attributes['last_name'],
                'gender'        => $this->normalizeSex()[$attributes['sex']],
                'status'        => isset($attributes['email']) ? Constants::STATUS_ACTIVE : Constants::STATUS_WAIT,
                'role'          => 'user',
            ],
            'provider_user_id' => $attributes['id'],
            'provider_id' => UserOauthKeyForm::getAvailableClients()['vkontakte'],
            'page' => 'id' . $attributes['id'],
        ];

        return $return_attributes;
    }
}
