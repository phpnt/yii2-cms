<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.02.2019
 * Time: 16:21
 */

namespace common\widgets\Carousel;

use common\widgets\Carousel\assets\LightSliderAsset;
use yii\base\Widget;

class Carousel extends Widget
{
    public $items;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $view = $this->getView();
        LightSliderAsset::register($view);

        return $this->render('@frontend/views/templates/control/blocks/carousel/index', [
            'widget' => $this,
            'items' => $this->items,
        ]);
    }
}