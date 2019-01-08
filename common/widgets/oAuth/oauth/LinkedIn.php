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
 * Авторизация с помощью LinkedIn
 * Class Yandex
 */
class LinkedIn extends \yii\authclient\clients\LinkedIn
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
     * Получение аттрибутов
     * @return array
     * @throws \yii\base\Exception
     */
    protected function initUserAttributes()
    {
        $attributes =  $this->api('people/~:(' . implode(',', $this->attributeNames) . ')', 'GET');

        $return_attributes = [
            'User' => [
                $this->email        => $attributes['email-address'],
                $this->first_name   => $attributes['first-name'],
                $this->last_name    => $attributes['last-name'],
                $this->status       => $this->statusActive
            ],
            'provider_user_id' => $attributes['id'],
            'provider_id' => UserOauthKey::getAvailableClients()['yandex'],
            'page' => $attributes['public-profile-url'],
        ];

        return $return_attributes;
    }
}
