<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.08.2018
 * Time: 10:37
 */

namespace common\widgets\JsTreeWidget;

use yii\bootstrap\Widget;
use yii\helpers\Json;
use common\widgets\JsTreeWidget\assets\JsTreeAsset;
use common\widgets\JsTreeWidget\assets\JsTreeBootstrapThemeAsset;

class FoldersAndElementsJsTreeWidget extends Widget
{
    public $getRootUrl;
    public $getChildUrl;

    public $elementUrl;

    public $options = [];

    // Setting of JsTree
    public $check_callback = true;
    public $themes = [
        'name' => 'proton',
        'responsive' => 'true',
    ];
    public $plugins = [
        "checkbox",
        "contextmenu",
        "dnd",
        "massload",
        "search",
        "sort",
        "state",
        "types",
        "unique",
        "wholerow",
        "changed",
        "conditionalselect"
    ];

    public function init()
    {
        parent::init();
        $this->id = $this->options['id'];
    }

    public function run()
    {
        $this->registerJs();

        return $this->render('folders-and-elements', [
            'widget' => $this,
        ]);
    }

    protected function registerJs()
    {
        if ($this->getRootUrl && $this->getChildUrl) {
            $view = $this->getView();
            JsTreeAsset::register($view);
            JsTreeBootstrapThemeAsset::register($view);

            $themes = Json::encode($this->themes);
            $plugins = Json::encode($this->plugins);

            $js = <<< JS
                $("#$this->id")
                /*.on("dblclick.jstree", function (e, data) {
                    console.log(data.id);
                })*/
                .jstree({
                    'core': {
                        'themes': $themes,
                        "check_callback" : $this->check_callback,
                        'data' : {
                            'url' : function (node) {
                                return node.id === '#' ? "$this->getRootUrl" : "$this->getChildUrl";
                            },
                            'data' : function (node) {
                                return {'id' : node.id}; 
                            },
                            'dataType' : 'json'
                        },
                    },
                    "plugins" : $plugins
                });
                $("#$this->id").bind("dblclick.jstree", function (event) {
                   var node = $(event.target).closest("li");
                   var data = node.data("jstree");
                   var id = (node[0].id);
                   var node = $('#$this->id').jstree(true).get_node(node[0].id)
                   if (node.icon == 'fa fa-file') {
                       $.pjax({
                            type: "GET",
                            url: "$this->elementUrl?id=" + id,
                            container: "#pjaxModalUniversal",
                            push: false,
                            timeout: 10000,
                            scrollTo: false
                        });
                   } 
                });
JS;
            $view->registerJs($js);
        }
    }
}