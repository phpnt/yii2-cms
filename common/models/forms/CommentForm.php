<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 18.01.2019
 * Time: 16:19
 */

namespace common\models\forms;

use common\models\Constants;
use Yii;
use common\models\extend\CommentExtend;
use yii\behaviors\TimestampBehavior;

class CommentForm extends CommentExtend
{
    public function rules()
    {
        $items = CommentExtend::rules();
        $items[] = [['text'], 'required', 'on' => ['create-comment', 'update-comment']];

        return $items;
    }

    /**
     * Автозаполнение полей создание и редактирование
     * профиля
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

        if ($this->scenario != 'default') {
            if (!$this->status) {
                $this->status = Constants::STATUS_DOC_WAIT;
            }
            $this->ip = Yii::$app->request->userIP;
            $this->user_agent = Yii::$app->request->userAgent;
            $this->user_id = Yii::$app->user->id;
        }

        return true;
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        return true;
    }
}