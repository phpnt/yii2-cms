<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use yii\widgets\Breadcrumbs;
use phpnt\bootstrapNotify\BootstrapNotify;
use common\widgets\JsTreeWidget\FoldersAndElementsJsTreeWidget;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $content string */
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $this->title ?></h1>
        <?= Breadcrumbs::widget(
            [
                'homeLink' => ['label' => Yii::t('app', 'Главная страница'), 'url' => ['/']],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
        <?= BootstrapNotify::widget() ?>
    </section>

    <section class="content">
        <div class="row">
            <?= $content ?>
        </div>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <strong><?=date('Y')?></strong>
    </div>
    <strong><a href="http://phpnt.com/" target="_blank">phpnt.com</a></strong> & <strong><a href="http://adt.ru/" target="_blank">adt.ru</a></strong>
</footer>

<aside class="control-sidebar control-sidebar-light">
    <div class="tab-content">
        <?php
        $items = [
            [
                'id' => 1,
                'parent' => '#',
                'text' => 'Папка 1',
            ],
            [
                'id' => 2,
                'parent' => '#',
                'text' => 'Папка 2',
            ],
            [
                'id' => 3,
                'parent' => 1,
                'text' => 'Элемент 1',
                'icon' => 'fa fa-file',
                'children' => false
            ],
            [
                'id' => 4,
                'parent' => 1,
                'text' => 'Элемент 2',
                'icon' => 'fa fa-file',
                'children' => false
            ],
            [
                'id' => 5,
                'parent' => 2,
                'text' => 'Элемент 3',
                'icon' => 'fa fa-file',
                'children' => false
            ],
        ];
        ?>

        <?= FoldersAndElementsJsTreeWidget::widget([
            'getRootUrl' => Url::to(['/document/manage/get-root']),
            'getChildUrl' => Url::to(['/document/manage/get-childs']),
            'elementUrl' => Url::to(['/document/folder-manage/view-folder']),   // при двойном клике на элемент, id gjlcnfdbncz
            'plugins' => [
                //"checkbox",
                "contextmenu",
                //"dnd",
                //"massload",
                //"search",
                //"sort",
                //"state",
                //"types",
                //"unique",
                //"wholerow",
                "changed",
                //"conditionalselect"
            ],
            'options' => []
        ]) ?>
    </div>
</aside>

<div class='control-sidebar-bg'></div>