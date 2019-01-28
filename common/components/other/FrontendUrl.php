<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 28.01.2019
 * Time: 8:57
 */

namespace common\components\other;

use Yii;
use yii\base\Object;

class FrontendUrl extends Object
{
    public function init()
    {
        parent::init();
    }

    public function getUrl() {
        if (isset(Yii::$app->params['frontendUrl']) && Yii::$app->params['frontendUrl'] != '' && filter_var(Yii::$app->params['frontendUrl'], FILTER_VALIDATE_URL)) {
            /* если указан домен фронтенда, в файле common\config\params.php, используем его */
            $url = Yii::$app->params['frontendUrl'].'/robots.txt';
            $urlHeaders = @get_headers($url);
            // проверяем ответ сервера на наличие кода: 200 - ОК
            if(strpos($urlHeaders[0], '200')) {
                return Yii::$app->params['frontendUrl'];
            }
        }
        $url = Yii::$app->urlManager->createAbsoluteUrl('/');
        $urlArray = explode('//', $url);
        $urlArray[1] = str_replace('/', '', $urlArray[1]);
        $url = $urlArray[0] . '//' . substr($urlArray[1], strpos($urlArray[1], ".")+1);// выводит ER@EXAMPLE.com
        return $url;
    }
}