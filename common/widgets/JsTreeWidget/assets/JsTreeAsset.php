<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.08.2018
 * Time: 10:31
 */

namespace common\widgets\JsTreeWidget\assets;

use yii\web\AssetBundle;

class JsTreeAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@bower/jstree';

    /**
     * @inherit
     */
    public $js = [
        'dist/jstree.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
