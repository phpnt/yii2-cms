<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.09.2018
 * Time: 17:51
 */

namespace common\widgets\MainMenu;

use common\models\Constants;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class MainMenu extends Widget
{
    public $optoinsNavBar = ['class' => 'navbar-default navbar-fixed-top',];
    public $optoinsNav = ['class' => 'navbar-nav navbar-right'];

    public $langMenu = [];

    private $items;
    private $isError;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $site = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => 'site'])
            ->one();

        /* В зависимости от доcтупа извлекаем навигацию (http://screenshot.ru/88dd5a039c55d5a8f8c3808fcb1e02d9) */
        if (Yii::$app->user->isGuest) {
            $navigation = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'status' => Constants::STATUS_DOC_ACTIVE,
                    'parent_id' => $site['id'],
                    'access' => [Constants::ACCESS_ALL, Constants::ACCESS_GUEST],
                ])
                ->orderBy(['position' => SORT_ASC])
                ->all();
        } else {
            $navigation = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'status' => Constants::STATUS_DOC_ACTIVE,
                    'parent_id' => $site['id'],
                    'access' => [Constants::ACCESS_ALL, Constants::ACCESS_USER],
                ])
                ->orderBy(['position' => SORT_ASC])
                ->all();
        }

        // узнаем используется мультиязычность или нет
        foreach ($navigation as $key => $item) {
            if ($item['alias'] == 'i18n') {
                unset($navigation[$key]);

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
            }
        }

        return $this->render('index', [
            'widget' => $this,
            'site' => $site,
            'navigation' => $navigation,
        ]);
    }

    public function label($code) {
        return isset(Yii::$app->urlManager->langLabels[$code]) ? Yii::$app->urlManager->langLabels[$code] : null;
    }
}