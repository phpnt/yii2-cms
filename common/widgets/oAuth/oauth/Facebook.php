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
 * Авторизация через Facebook
 * Class Facebook
 */
class Facebook extends \yii\authclient\clients\Facebook
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
            'popupHeight' => 600
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
            'female' => $this->female
        ];
    }

    /**
     * Получение аттрибутов
     * @return array
     * @throws \yii\base\Exception
     */
    protected function initUserAttributes()
    {
        $attributes = $this->api('me', 'GET', [
            'fields' => implode(',', [
                'id',
                'email',
                'first_name',
                'last_name',
                'gender'
            ]),
        ]);

        $return_attributes = [
            'OAuthForm' => [
                'email'         => $attributes['email'],
                'first_name'    => $attributes['first_name'],
                'last_name'     => $attributes['last_name'],
                'gender'        => $this->normalizeSex()[$attributes['gender']],
                'status'        => Constants::STATUS_ACTIVE,
                'role'          => 'user',
            ],
            'provider_user_id' => $attributes['id'],
            'provider_id' => UserOauthKeyForm::getAvailableClients()['facebook'],
            'page' => $attributes['id'],
        ];

        return $return_attributes;
    }
}
