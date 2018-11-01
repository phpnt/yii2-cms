<?php
/**
 * @package   yii2-user
 * @author    Yuri Shekhovtsov <shekhovtsovy@yandex.ru>
 * @copyright Copyright &copy; Yuri Shekhovtsov, lowbase.ru, 2015 - 2016
 * @version   1.0.0
 */

namespace backend\modules\user\assets;

use yii\web\AssetBundle;

/**
 * Подключение CSS и JS
 */
class UserAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/user/assets';

    public $css = [
        'css/user-module.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
