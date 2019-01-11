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
 * Авторизация с помощью Яндекса
 * Class Yandex
 */
class Yandex extends \yii\authclient\clients\Yandex
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
            'OAuthForm' => [
                'email'        => $attributes['emails'][0],
                'first_name'   => $attributes['first_name'],
                'last_name'    => $attributes['last_name'],
                'gender'       => $this->normalizeSex()[$attributes['sex']],
                'status'        => Constants::STATUS_ACTIVE,
                'role'          => 'user',
            ],
            'provider_user_id' => $attributes['id'],
            'provider_id' => UserOauthKeyForm::getAvailableClients()['yandex'],
            'page' => null,
        ];

        return $return_attributes;
    }
}
