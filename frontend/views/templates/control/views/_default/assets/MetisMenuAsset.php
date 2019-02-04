<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 14:54
 */

namespace frontend\views\templates\control\views\_default\assets;

use yii\web\AssetBundle;

class MetisMenuAsset extends AssetBundle
{
    public $sourcePath = '@frontend/views/templates/control/views/_default';

    public $css = [
        'css/mm.css'
    ];

    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'phpnt\metismenu\MetisMenuAsset',
    ];
}
