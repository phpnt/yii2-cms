<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 10.02.2019
 * Time: 7:13
 */

namespace frontend\views\templates\control\blocks\sidebar\assets;

use yii\web\AssetBundle;

class SidebarAsset extends AssetBundle
{
    public $sourcePath = '@frontend/views/templates/control/blocks/sidebar';

    public $css = [
        'css/sidebar.css'
    ];

    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'phpnt\metismenu\MetisMenuAsset',
    ];
}
