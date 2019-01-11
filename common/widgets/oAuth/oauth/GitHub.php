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
 * Авторизация через GitHub
 * Class GitHub
 */
class GitHub extends \yii\authclient\clients\GitHub
{
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
     * Инициализация
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(' ', [
                'user',
                'user:email',
            ]);
        }
    }

    /**
     * Получение аттрибутов
     * @return array
     * @throws \yii\base\Exception
     */
    protected function initUserAttributes()
    {
        $attributes = $this->api('user', 'GET');

        $emails = $this->api('user/emails', 'GET');

        $verifiedEmail = '';

        foreach ($emails as $email) {
            if ($email['verified'] && $email['primary']) {
                $verifiedEmail = $email['email'];
            }
        }

        $return_attributes = [
            'OAuthForm' => [
                'email'         => $verifiedEmail,
                'first_name'    => $attributes['login'],
                'last_name'     => $attributes['avatar_url'],
                'gender'        => null,
                'status'        => Constants::STATUS_ACTIVE,
                'role'          => 'user',
            ],
            'provider_user_id' => $attributes['id'],
            'provider_id' => UserOauthKeyForm::getAvailableClients()['github'],
            'page' => $attributes['login'],
        ];

        return $return_attributes;
    }
}
