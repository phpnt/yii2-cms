<?php
/**
 * Created by PhpStorm.
 * User: Raketos - http://raketos.ru
 * Date: 06.04.2017
 * Time: 13:12
 */

namespace common\widgets\Carousel\assets;

use Yii;

class LightSliderAsset extends \yii\web\AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@bower/lightslider/dist';
    /**
     * @inherit
     */
    public $css = [
        'css/lightslider.min.css',
    ];
    public $js = [
        'js/lightslider.min.js',
    ];
    public $images = [
        'img/controls.png'
    ];

    public function init()
    {
        $this->registerJs();
        parent::init();
    }

    protected function registerJs()
    {
        $js = <<<JS
            $(document).ready(function() {
                function loadSlider() {
                    $(".light-slider").lightSlider({
                        item: 4,
                        autoWidth: false,
                        slideMove: 1, // slidemove will be 1 if loop is true
                        slideMargin: 10,
            
                        addClass: '',
                        mode: "slide",
                        useCSS: true,
                        cssEasing: 'ease', //'cubic-bezier(0.25, 0, 0.25, 1)',//
                        easing: 'linear', //'for jquery animation',////
            
                        speed: 400, //ms'
                        auto: false,
                        pauseOnHover: false,
                        loop: false,
                        slideEndAnimation: true,
                        pause: 2000,
            
                        keyPress: false,
                        controls: false,
                        prevHtml: '',
                        nextHtml: '',
            
                        rtl:false,
                        adaptiveHeight:false,
            
                        vertical:false,
                        verticalHeight:500,
                        vThumbWidth:100,
            
                        thumbItem:10,
                        pager: false,
                        gallery: false,
                        galleryMargin: 5,
                        thumbMargin: 5,
                        currentPagerPosition: 'middle',
            
                        enableTouch:true,
                        enableDrag:true,
                        freeMove:true,
                        swipeThreshold: 40,
            
                        responsive : [
                            {
                                breakpoint:800,
                                settings: {
                                    item:3,
                                    slideMove:1,
                                    slideMargin:6,
                                    controls: true,
                                  }
                            },
                            {
                                breakpoint:480,
                                settings: {
                                    item:2,
                                    slideMove:1,
                                    controls: true,
                                  }
                            }
                        ],
            
                        onBeforeStart: function (el) {},
                        onSliderLoad: function (el) {},
                        onBeforeSlide: function (el) {},
                        onAfterSlide: function (el) {},
                        onBeforeNextSlide: function (el) {},
                        onBeforePrevSlide: function (el) {}
                    });
                }
                
                loadSlider();
                
           
                function resizeSlider() {
                    setTimeout(function() {
                    loadSlider();
                    }, 400);
                }
            });
JS;
        Yii::$app->view->registerJs($js);
        return $this;
    }
}