<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\widgets\oAuth\oauth;

use common\models\Constants;
use common\widgets\oAuth\models\UserOauthKey;

/**
 * Авторизация с помощью Twitter
 * Class Twitter
 */
class Twitter extends \yii\authclient\clients\Twitter
{
    public $email       = 'email';
    public $first_name  = 'first_name';
    public $last_name   = 'last_name';

    public $gender      = 'gender';
    public $female      = 1;
    public $male        = 2;

    public $status      = 'status';

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
            'User' => [
                $this->email        => '',
                $this->first_name   => isset($fullName[0]) ? $fullName[0] : null,
                $this->last_name    => isset($fullName[1]) ? $fullName[1] : null,
                $this->gender       => $this->male,
                $this->status       => Constants::STATUS_WAIT
            ],
            'provider_user_id' => $attributes['id'],
            'provider_id' => UserOauthKey::getAvailableClients()['twitter'],
            'page' => $attributes['screen_name'],
        ];

        return $return_attributes;
    }
}
