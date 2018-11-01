<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 21.09.2018
 * Time: 10:43
 */

namespace common\widgets\JsTreeWidget;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Json;
use common\widgets\JsTreeWidget\assets\JsTreeAsset;
use common\widgets\JsTreeWidget\assets\JsTreeBootstrapThemeAsset;
use yii\helpers\Url;

class FoldersJsTreeWidget extends Widget
{
    public $items;

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

        return $this->render('folders', [
            'widget' => $this,
        ]);
    }

    protected function registerJs()
    {
        $view = $this->getView();
        JsTreeAsset::register($view);
        JsTreeBootstrapThemeAsset::register($view);

        $items = Json::encode($this->items);
        $themes = Json::encode($this->themes);
        $plugins = Json::encode($this->plugins);
        $menu = Json::encode($this->contextMenu());

        $urlViewElements = Url::to(['/document/folder-manage/view-elements']);

        $js = <<< JS
                $("#$this->id")
                .on("dblclick.jstree", function (e, data) {
                    console.log(data);
                })
                .jstree({
                    'core': {
                        'themes': $themes,
                        "check_callback" : $this->check_callback,
                        'data' : $items,
                        "types" : {
                            "default" : {
                                "icon" : "glyphicon glyphicon-flash"
                            },
                            "MY-DRAGGABLE-TYPE" : {
                                "icon" : "glyphicon glyphicon-info-sign"
                            },
                        },
                    },
                    "plugins" : $plugins,
                    "contextmenu":{         
                        "items": function(node) {
                            var tree = $("#$this->id").jstree(true);
                            return {$menu};
                        }
                    }
                });
                // событие двойного щелчка по папке  
                $("#$this->id").bind("dblclick.jstree", function (event) {
                    var node = $(event.target).closest("li");
                    var data = node.data("jstree");
                    var id = (node[0].id);
                });
                // событие одного щелчка по папке  
                $("#$this->id").bind("select_node.jstree", function (event, data) {
                    var id = data.node.id;
                    $.pjax({
                        type: "GET",
                        url: "$urlViewElements?id_folder=" + id,
                        container: "#pjax-grid-elements-block", 
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    })     
                });

JS;
        $view->registerJs($js);
    }

    /*
     * Формирование контекстного меню в зависимости от ролей
     * */
    private function contextMenu() {
        $menu = [];
        $menu['Create'] = [
            'separator_before' => false,
            'separator_after' => false,
            'label' => Yii::t('app', 'Создать папку'),
            'icon' => 'fa fa-plus',
            '_disabled' => Yii::$app->user->can('document/folder-manage/create-folder') ? false : true,
            'action' => new \yii\web\JsExpression(
                'function (obj) {
                    var id = node.id;
                    $.pjax({
                        type: "GET",
                        url: "/document/folder-manage/create-folder?parent_id=" + id,
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    });
                }'
            ),
        ];
        $menu['View'] = [
            'separator_before' => false,
            'separator_after' => false,
            'label' => Yii::t('app', 'Просмотр папки'),
            'icon' => 'fa fa-eye',
            '_disabled' => Yii::$app->user->can('document/folder-manage/view-folder') ? false : true,
            'action' => new \yii\web\JsExpression(
                'function (obj) {
                    var id = node.id;
                    $.pjax({
                        type: "GET",
                        url: "/document/folder-manage/view-folder?id=" + id,
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    });
                }'
            ),
        ];
        $menu['Update'] = [
            'separator_before' => false,
            'separator_after' => false,
            'label' => Yii::t('app', 'Изменить папку'),
            'icon' => 'fa fa-pen',
            '_disabled' => Yii::$app->user->can('document/folder-manage/update-folder') ? false : true,
            'action' => new \yii\web\JsExpression(
                'function (obj) {
                    var id = node.id;
                    $.pjax({
                        type: "GET",
                        url: "/document/folder-manage/update-folder?id=" + id,
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    });
                }'
            ),
        ];
        $menu['Delete'] = [
            'separator_before' => false,
            'separator_after' => false,
            'label' => Yii::t('app', 'Удалить папку'),
            'icon' => 'fa fa-trash',
            '_disabled' => Yii::$app->user->can('document/folder-manage/delete-folder') ? false : true,
            'action' => new \yii\web\JsExpression(
                'function (obj) {
                    var id = node.id;
                    $.pjax({
                        type: "GET",
                        url: "/document/folder-manage/confirm-delete-folder?id=" + id, 
                        container: "#pjaxModalUniversal",
                        push: false,
                        timeout: 10000,
                        scrollTo: false
                    });
                }'
            ),
        ];

        return $menu;
    }
}