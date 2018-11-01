<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 25.08.2018
 * Time: 15:33
 */

namespace backend\assets;

use yii\web\AssetBundle;

class TranslateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/translate.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
