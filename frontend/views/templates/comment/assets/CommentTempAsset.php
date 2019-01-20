<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 10.12.2018
 * Time: 17:24
 */

namespace frontend\views\templates\comment\assets;

use yii\web\AssetBundle;

class CommentTempAsset extends AssetBundle
{
    public $sourcePath = '@frontend/views/templates/comment';

    public $css = [
        'css/comment.css',
    ];

    public $js = [
    ];
}
