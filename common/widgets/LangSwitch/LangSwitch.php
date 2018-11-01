<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 28.10.2018
 * Time: 21:37
 */

namespace common\widgets\LangSwitch;

use common\models\Constants;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class LangSwitch extends Widget
{
    public $langMenu = [];

    private $items;
    private $isError;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $route = Yii::$app->controller->route;
        $params = $_GET;
        $this->isError = $route === Yii::$app->errorHandler->errorAction;

        array_unshift($params, '/' . $route);

        foreach (Yii::$app->urlManager->languages as $language) {
            $isWildcard = substr($language, -2) === '-*';
            if ($isWildcard) {
                $language = substr($language, 0, 2);
            }
            $params['language'] = $language;
            $params['url-language'] = $language;
            $this->items[] = [
                'label' => $this->label($language),
                'url'   => $params,
            ];
        }

        /* @var $item array */
        foreach ($this->items as $item) {
            if (Yii::$app->language == $item['url']['language']) {
                $this->langMenu['label'] = $item['label'];
            } else {
                $this->langMenu['items'][] = [
                    'label' => $item['label'],
                    'url' =>  Url::current(['language' => $item['url']['language']]),
                ];
            }

        }

        return $this->render('index', [
            'widget' => $this,
        ]);
    }

    public function label($code) {
        return isset(Yii::$app->urlManager->langLabels[$code]) ? Yii::$app->urlManager->langLabels[$code] : null;
    }
}