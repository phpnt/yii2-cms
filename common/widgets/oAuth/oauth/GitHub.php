<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\widgets\oAuth\oauth;

use common\widgets\oAuth\models\UserOauthKey;

/**
 * Авторизация через GitHub
 * Class GitHub
 */
class GitHub extends \yii\authclient\clients\GitHub
{
    public $email       = 'email';
    public $first_name  = 'first_name';
    public $last_name   = 'last_name';
    public $avatar      = 'avatar';

    public $gender      = 'gender';
    public $female      = 1;
    public $male        = 2;

    public $status          = 'status';
    public $statusActive    = 1;
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
            'User' => [
                $this->email        => $verifiedEmail,
                $this->first_name   => $attributes['login'],
                $this->avatar       => $attributes['avatar_url'],
                $this->gender       => $this->male,
                $this->status       => $this->statusActive
            ],
            'provider_user_id' => $attributes['id'],
            'provider_id' => UserOauthKey::getAvailableClients()['github'],
            'page' => $attributes['login'],
        ];

        return $return_attributes;
    }
}
