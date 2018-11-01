<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 12:17
 */

namespace common\models\forms;

use common\models\Constants;
use Yii;
use common\models\extend\BasketExtend;

class BasketForm extends BasketExtend
{
    public function beforeValidate()
    {
        parent::beforeValidate();

        if ($this->scenario != 'show-all') {
            if (Yii::$app->user->isGuest) {
                // с одним IP обновляется раз в сутки
                $this->status = Constants::STATUS_DOC_WAIT;
                $this->created_at = time();
                $this->ip = Yii::$app->request->userIP;
                $this->user_agent = Yii::$app->request->userAgent;
            } else {
                $this->status = Constants::STATUS_DOC_WAIT;
                $this->created_at = time();
                $this->ip = Yii::$app->request->userIP;
                $this->user_agent = Yii::$app->request->userAgent;
                $this->user_id = Yii::$app->user->id;
            }
        }

        return true;
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        return true;
    }
}