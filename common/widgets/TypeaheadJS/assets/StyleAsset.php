<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 29.07.2018
 * Time: 15:54
 */

namespace common\widgets\TypeaheadJS\assets;

use yii\web\AssetBundle;

class StyleAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@common/widgets/TypeaheadJS';

    /**
     * @inherit
     */
    public $css = [
        'css/typeahead.css',
    ];
}