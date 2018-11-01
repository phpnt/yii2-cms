<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:18
 */

namespace common\models\forms;

use Yii;
use common\models\extend\AuthItemExtend;
use yii\behaviors\TimestampBehavior;

class AuthItemForm extends AuthItemExtend
{
    public $permission_list;

    public function rules()
    {
        $items = AuthItemExtend::rules();
        $items[] = [['permission_list'], 'each', 'rule' => ['string']];

        return $items;
    }

    public function attributeLabels()
    {
        $items = AuthItemExtend::attributeLabels();
        $items['permission_list'] = Yii::t('app', 'Список разрешений');

        return $items;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value' => time(),
        ]];
    }

    public function beforeValidate()
    {
        parent::beforeValidate();

        if (!$this->rule_name) {
            $this->rule_name = null;
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->permission_list) {
            AuthItemChildForm::deleteAll(['parent' => $this->name]);
            foreach ($this->permission_list as $item) {
                $modelAuthItemChildForm = new AuthItemChildForm();
                $modelAuthItemChildForm->parent = $this->name;
                $modelAuthItemChildForm->child = $item;
                $modelAuthItemChildForm->save();
            }
        }
    }
}