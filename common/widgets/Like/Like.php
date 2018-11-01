<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 26.10.2018
 * Time: 21:44
 */

namespace common\widgets\Like;

use yii\base\Widget;

class Like extends Widget
{
    public $document_id;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $likes = (new \yii\db\Query())
            ->select(['*'])
            ->from('like')
            ->where(['document_id' => $this->document_id])
            ->count();

        return $this->render('index', [
            'document_id' => $this->document_id,
            'likes' => $likes
        ]);
    }
}