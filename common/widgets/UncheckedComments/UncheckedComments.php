<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.01.2019
 * Time: 12:28
 */

namespace common\widgets\UncheckedComments;

use common\models\Constants;
use yii\base\Widget;

/*
 * Виджет подсчета не провереных комментариев
 * */
class UncheckedComments extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
// подсчет процентов
        $countComment = (new \yii\db\Query())
            ->from('comment')
            ->where(['status' => Constants::STATUS_DOC_WAIT])
            ->count();

        return $this->render('index', [
            'countComment' => $countComment,
        ]);
    }
}