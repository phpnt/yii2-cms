<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 12:16
 */

namespace common\models\extend;

use common\models\Basket;
use common\models\Constants;

/**
 * @property array $statusList
 * @property string $statusProduct
 */
class BasketExtend extends Basket
{
    public static function getStatusList()
    {
        return [
            Constants::STATUS_DOC_WAIT =>  'Ожидание',
            Constants::STATUS_DOC_ACTIVE => 'Оплачен',
            Constants::STATUS_DOC_BLOCKED => 'Заблокирован',
        ];
    }

    /**
     * Возвращает статус пользователя
     */
    public function getStatusProduct()
    {
        switch ($this->status) {
            case Constants::STATUS_DOC_WAIT:
                return '<span class="label label-warning">'.$this->statusList[Constants::STATUS_DOC_WAIT].'</span>';
                break;
            case Constants::STATUS_DOC_ACTIVE:
                return '<span class="label label-success">'.$this->statusList[Constants::STATUS_DOC_ACTIVE].'</span>';
                break;
            case Constants::STATUS_DOC_BLOCKED:
                return '<span class="label label-danger">'.$this->statusList[Constants::STATUS_DOC_BLOCKED].'</span>';
                break;
        }
        return false;
    }
}