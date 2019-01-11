<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:30
 */

namespace common\models\extend;

use common\models\forms\UserForm;
use common\models\UserOauthKey;

/**
 * @property UserForm $user
 */
class UserOauthKeyExtend extends UserOauthKey
{
    /**
     * Поддерживаемые социальные сети
     * @return array
     */
    public static function getAvailableClients()
    {
        return [
            'vkontakte' => 1,
            'google'    => 2,
            'facebook'  => 3,
            'github'    => 4,
            'linkedin'  => 5,
            'yandex'    => 7,
            'twitter'   => 8
        ];
    }

    /**
     * Приставки для формирования
     * личных страниц пользователей в
     * социальных сетях
     * @return array
     */
    public static function getSites()
    {
        return [
            1 => '//vk.com/id',
            2 => '//plus.google.com/',
            3 => '//wwww.facebook.com/',
            4 => '//github.com/',
            7 => '',
            8 => '//twitter.com/'
        ];
    }

    /**
     * Возвращает количество активированных социальных сетей
     * @param $user_id - ID пользователя
     * @return int|string
     */
    public static function isOAuth($user_id)
    {
        return self::find()->where(['user_id' => $user_id])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserForm::class, ['id' => 'user_id']);
    }
}