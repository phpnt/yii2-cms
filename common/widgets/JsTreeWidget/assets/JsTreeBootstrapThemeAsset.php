<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.08.2018
 * Time: 10:36
 */

namespace common\widgets\JsTreeWidget\assets;

use yii\web\AssetBundle;

class JsTreeBootstrapThemeAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@bower/jstree-bootstrap-theme';

    /**
     * @inherit
     */
    public $css = [
        'dist/themes/proton/style.min.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();
    }
}