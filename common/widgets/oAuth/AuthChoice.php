<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\widgets\oAuth;

use yii\authclient\widgets\AuthChoiceAsset;
use yii\authclient\widgets\AuthChoiceItem;
use yii\authclient\widgets\AuthChoiceStyleAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\authclient\ClientInterface;

/**
 * Авторизация через соц. сети.
 * Используется стандартный код класса.
 * Изменения касаются лишь тегов оформления
 * отображения
 *
 * Class AuthChoice
 */
class AuthChoice extends \yii\authclient\widgets\AuthChoice
{
    public $clientCssClass = 'col-xs-1'; // 12 иконок в ряд

    /**
     * Добавляем обрамление div
     * @param ClientInterface $client
     * @param null $text
     * @param array $htmlOptions
     * @throws InvalidConfigException
     */
    public function clientLink($client, $text = null, array $htmlOptions = [])
    {
        echo Html::beginTag('div',
            [
                'class' => $this->clientCssClass,
                'style' => 'float: left; padding-right: 20px;',
            ]);
        $text = Html::tag('span', $text, [
            'class' => 'auth-icon ' . $client->getName(),
        ]);

        if (!array_key_exists('class', $htmlOptions)) {
            $htmlOptions['class'] = 'auth-link ' . $client->getName();
        }

        $viewOptions = $client->getViewOptions();
        if (empty($viewOptions['widget'])) {
            if ($this->popupMode) {
                if (isset($viewOptions['popupWidth'])) {
                    $htmlOptions['data-popup-width'] = $viewOptions['popupWidth'];
                }
                if (isset($viewOptions['popupHeight'])) {
                    $htmlOptions['data-popup-height'] = $viewOptions['popupHeight'];
                }
            }
            $htmlOptions['data-pjax'] = '0';
            echo Html::a($text, $this->createClientUrl($client), $htmlOptions).'<br>';
        } else {
            $widgetConfig = $viewOptions['widget'];
            if (!isset($widgetConfig['class'])) {
                throw new InvalidConfigException('Widget config "class" parameter is missing');
            }
            /* @var $widgetClass Widget */
            $widgetClass = $widgetConfig['class'];
            if (!(is_subclass_of($widgetClass, AuthChoiceItem::className()))) {
                throw new InvalidConfigException('Item widget class must be subclass of "' . AuthChoiceItem::className() . '"');
            }
            unset($widgetConfig['class']);
            $widgetConfig['client'] = $client;
            $widgetConfig['authChoice'] = $this;
            echo $widgetClass::widget($widgetConfig);
        }
        echo Html::endTag('div');
    }

    /**
     * Меняем ul на div
     * @throws InvalidConfigException
     */
    protected function renderMainContent()
    {
        foreach ($this->getClients() as $externalService) {
            $this->clientLink($externalService);
        }
    }

}
