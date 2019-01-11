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
 * Авторизация с помощью Twitter
 * Class Twitter
 */
class Twitter extends \yii\authclient\clients\Twitter
{
    public $female      = 1;
    public $male        = 2;

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
     * Получение аттрибутов
     * @return array
     * @throws \yii\base\Exception
     */
    protected function initUserAttributes()
    {
        $attributes = $this->api('account/verify_credentials.json', 'GET');

        $fullName = explode(' ', $attributes['name']);

        $return_attributes = [
            'OAuthForm' => [
                'email'         => $attributes['id'] . '_' . time(),
                'first_name'    => isset($fullName[0]) ? $fullName[0] : null,
                'last_name'     => isset($fullName[1]) ? $fullName[1] : null,
                'gender'        => null,
                'status'        => Constants::STATUS_WAIT,
                'role'          => 'user',
            ],
            'provider_user_id' => $attributes['id'],
            'provider_id' => UserOauthKeyForm::getAvailableClients()['twitter'],
            'page' => $attributes['screen_name'],
        ];

        return $return_attributes;
    }
}
