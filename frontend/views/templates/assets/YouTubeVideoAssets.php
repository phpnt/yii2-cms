<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 10.12.2018
 * Time: 17:24
 */

namespace frontend\views\templates\youtubeVideoTemplate\assets;

use yii\web\AssetBundle;

class YouTubeVideoAssets extends AssetBundle
{
    public $sourcePath = '@frontend/views/templates/youtubeVideoTemplate';

    public $css = [
        'css/post.css',
        'css/mm.css'
    ];

    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'phpnt\metismenu\MetisMenuAsset',
    ];
}
