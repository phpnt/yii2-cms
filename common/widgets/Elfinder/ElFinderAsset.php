<?php

namespace common\widgets\Elfinder;

use yii\web\AssetBundle;

/**
 * Class ElFinderAsset
 * @package alexantr\elfinder
 */
class ElFinderAsset extends AssetBundle
{
    public $sourcePath = '@vendor/studio-42/elfinder';
    public $css = [
        'css/elfinder.min.css',
        'css/theme.css',
    ];
    public $js = [
        'js/elfinder.min.js',
    ];
    public $publishOptions = [
        'except' => [
            'files/',
            'php/',
            '*.html',
            '*.php',
        ],
        'caseSensitive' => false,
    ];
    public $depends = [
        'yii\jui\JuiAsset',
    ];
}
