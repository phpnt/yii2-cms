<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.09.2018
 * Time: 19:57
 */

namespace common\components\other;

use common\models\Constants;
use yii\base\Object;

class UserAccess extends Object
{
    public function init()
    {
        parent::init();
    }

    public function getUserAccess($access) {
        if ($access == Constants::ACCESS_ALL) {
            return ['?', '@'];
        } elseif ($access == Constants::ACCESS_GUEST) {
            return ['?'];
        } else {
            return ['@'];
        }
    }
}