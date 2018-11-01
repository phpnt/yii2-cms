<?php

namespace common\widgets\Elfinder;

use Yii;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class ElFinder extends Widget
{
    /**
     * @var array Client settings
     * @see https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
     */
    public $settings = [];

    /**
     * @var bool Resolves conflict between Bootstrab 3 btn and jQuery UI btn. Enable if using widget on page with BS3
     * @see https://github.com/twbs/bootstrap/issues/6094
     */
    public $buttonNoConflict = false;

    public $elfinderOptions;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->settings['url'] = Url::toRoute(['/elfinder/connector', 'options' => $this->elfinderOptions]);

        if (!isset($this->settings['lang'])) {
            $this->settings['lang'] = Yii::$app->language;
        }

        $this->settings['customData'] = [
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ];
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = $this->getId();

        $view = $this->getView();

        if ($this->buttonNoConflict) {
            $view->registerJs('jQuery.fn.btn = jQuery.fn.button.noConflict();');
        }

        $bundle = ElFinderAsset::register($view);

        if (is_file($bundle->basePath . '/js/i18n/elfinder.' . $this->settings['lang'] . '.js')) {
            $view->registerJsFile($bundle->baseUrl . '/js/i18n/elfinder.' . $this->settings['lang'] . '.js', [
                'depends' => [ElFinderAsset::className()],
            ]);
        }

        $this->settings['soundPath'] = $bundle->baseUrl . '/sounds';

        if (!isset($this->settings['height'])) {
            $this->settings['height'] = new JsExpression('jQuery(window).height() - 2');
        }

        $settings = Json::encode($this->settings);

        $view->registerJs("jQuery('#$id').elfinder($settings);");
        echo "<div id=\"{$id}\"></div>";
    }
}
