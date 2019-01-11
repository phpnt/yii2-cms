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
 * Авторизация через Google plus
 * Class Google
 */
class Google extends \yii\authclient\clients\Google
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
            'female' => $this->female,
            'male' => $this->male,
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
                'profile',
                'email',
            ]);
        }
    }

    /**
     * Получение аттрибутов
     * @return array
     * @throws \yii\base\Exception
     */
    protected function initUserAttributes(): iterable
    {
        $attributes = $this->api('people/me', 'GET');

        $return_attributes = [
            'OAuthForm' => [
                'email'         => $attributes['emails'][0]['value'],
                'first_name'    => $attributes['name']['givenName'],
                'last_name'     => $attributes['name']['familyName'],
                'gender'        => isset($attributes['gender']) ? $this->normalizeSex()[$attributes['gender']] : '',
                'status'        => Constants::STATUS_ACTIVE,
                'role'          => 'user',
            ],
            'provider_user_id' => $attributes['id'],
            'provider_id' => UserOauthKeyForm::getAvailableClients()['google'],
            'page' => $attributes['id'],
        ];
        return $return_attributes;
    }
}
