<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.01.2019
 * Time: 12:28
 */

namespace common\widgets\Comment;

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
        $parent = (new \yii\db\Query())
        ->select(['*'])
        ->from('document')
        ->where([
            'alias' => 'comments',
        ])
        ->one();

        $countComment = (new \yii\db\Query())
            ->select(['document.*'])
            ->from('document')
            ->where([
                'parent_id' => $parent['id'],
                'status' => Constants::STATUS_DOC_WAIT
            ])
            ->count();

        return $this->render('index', [
            'countComment' => $countComment,
        ]);
    }
}