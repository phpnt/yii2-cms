<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 29.07.2018
 * Time: 15:52
 */

namespace common\widgets\TypeaheadJS\assets;

use yii\web\AssetBundle;

class TypeaheadAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@bower/typeahead.js';

    /**
     * @inherit
     */
    public $js = [
        'dist/typeahead.bundle.js',
        'dist/bloodhound.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}