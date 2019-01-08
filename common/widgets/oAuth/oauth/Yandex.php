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
 * Авторизация с помощью Яндекса
 * Class Yandex
 */
class Yandex extends \yii\authclient\clients\Yandex
{
    public $email       = 'email';
    public $first_name  = 'first_name';
    public $last_name   = 'last_name';
    public $avatar      = 'avatar';

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
     * Преобразование пола
     * @return array
     */
    public function normalizeSex()
    {
        return [
            'male' => $this->male,
            'female' => $this->female,
            null => null
        ];
    }

    /**
     * Получение аттрибутов
     * @return array
     * @throws \yii\base\Exception
     */
    protected function initUserAttributes()
    {
        $attributes =  $this->api('info', 'GET');

        $return_attributes = [
            'User' => [
                $this->email        => $attributes['emails'][0],
                $this->first_name   => $attributes['first_name'],
                $this->last_name    => $attributes['last_name'],
                $this->gender       => $this->normalizeSex()[$attributes['sex']],
                $this->status       => Constants::STATUS_ACTIVE
            ],
            'provider_user_id' => $attributes['id'],
            'provider_id' => UserOauthKey::getAvailableClients()['yandex'],
            'page' => null,
        ];

        return $return_attributes;
    }
}
