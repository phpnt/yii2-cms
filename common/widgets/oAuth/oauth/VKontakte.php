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
 * Авторизация с помощью Вконтакте
 * Class VKontakte
 */
class VKontakte extends \yii\authclient\clients\VKontakte
{
    public $email       = 'email';
    public $first_name  = 'first_name';
    public $last_name   = 'last_name';

    public $gender      = 'gender';
    public $female      = 1;
    public $male        = 2;

    public $status          = 'status';

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
            'User' => [
                $this->first_name   => $attributes['first_name'],
                $this->last_name    => $attributes['last_name'],
                $this->gender       => $this->normalizeSex()[$attributes['sex']],
                $this->email        => isset($attributes['email']) ? $attributes['email'] : null,
                $this->status       => isset($attributes['email']) ? Constants::STATUS_ACTIVE : Constants::STATUS_WAIT
            ],
            'provider_user_id' => $attributes['uid'],
            'provider_id' => UserOauthKey::getAvailableClients()['vkontakte'],
            'page' => $attributes['uid'],
        ];

        return $return_attributes;
    }
}
