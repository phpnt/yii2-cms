<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.08.2018
 * Time: 13:31
 */

namespace common\models\forms;

use common\models\Constants;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Подтверждение электронной почты
 * Class EmailConfirm
 * @package lowbase\user\models
 */
class EmailConfirm extends Model
{
    /**
     * @var UserForm
     */
    private $_user;

    /**
     * @param  string $token  - токен
     * @param  array  $config - параметры
     * @throws \yii\base\InvalidParamException - при пустом или неправильном токене
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Yii::t('app', 'Отсутствует код подтверждения.'));
        }
        $this->_user = UserForm::findByEmailConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidParamException(Yii::t('app', 'Неверный токен.'));
        }
        parent::__construct($config);
    }

    /**
     * Подтверждение электронной почты
     *
     * @return bool|int
     */
    public function confirmEmail()
    {
        $user = $this->_user;
        $user->status = Constants::STATUS_ACTIVE;
        $user->role = 'user';
        $user->removeEmailConfirmToken();   // Удаление токена подтверждения электронной почты

        return (($user->save())) ? $user->id : false;
    }
}