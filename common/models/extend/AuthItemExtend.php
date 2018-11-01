<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:18
 */

namespace common\models\extend;

use Yii;
use common\models\AuthItem;
use common\models\Constants;
use yii\helpers\ArrayHelper;

/*
 * @property array $permissionList
 * @property array $permissionList
 * @property array $ruleList
 * @property array $typeIs
 * @property array $typeList
 */
class AuthItemExtend extends AuthItem
{
    /**
     * Роль и разрешение
     * @return array
     */
    public function getTypeList()
    {
        return [
            1 => Yii::t('app', 'Роль'),
            2 => Yii::t('app', 'Разрешение'),
        ];
    }

    /**
     * Роль или разрешение
     * @return string
     */
    public function getTypeIs()
    {
        if ($this->type == Constants::TYPE_ROLE) {
           return Yii::t('app', 'Роль');
        }

        return Yii::t('app', 'Разрешение');
    }

    /**
     * Все правила
     * @return array
     */
    public function getRuleList() {
        $data = (new \yii\db\Query())
            ->select(['*'])
            ->from('auth_rule')
            ->orderBy(['name' => SORT_ASC])
            ->all();

        return ArrayHelper::map($data, 'name', 'name');
    }

    /**
     * Все роли и разрешения
     * @return array
     */
    public function getPermissionList() {
        $data = (new \yii\db\Query())
            ->select(['*'])
            ->from('auth_item')
            ->orderBy(['description' => SORT_DESC])
            ->all();

        return ArrayHelper::map($data, 'name', 'description');
    }

    /**
     * Используемые разрешения
     * @return array
     */
    public function getUsedPermissionList() {
        $data = (new \yii\db\Query())
            ->select(['*'])
            ->from('auth_item_child')
            ->where(['parent' => $this->name])
            ->all();

        $items = [];
        foreach ($data as $item) {
            $items[] = $item['child'];
        }

        return $items;
    }

    /**
     * Не используемые разрешения
     * @return array
     */
    public function getAllPermissionList() {
        $data = (new \yii\db\Query())
            ->select(['*'])
            ->from('auth_item')
            ->where(['!=', 'name', $this->name])
            ->orderBy(['description' => SORT_ASC])
            ->all();

        return ArrayHelper::map($data, 'name', 'description');
    }
}