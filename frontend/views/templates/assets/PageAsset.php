<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 10.12.2018
 * Time: 17:24
 */

namespace frontend\views\templates\assets;

use yii\web\AssetBundle;

class PageAsset extends AssetBundle
{
    public $sourcePath = '@frontend/views/templates';

    public $css = [
        'css/page.css',
    ];

    public $js = [
    ];
}
