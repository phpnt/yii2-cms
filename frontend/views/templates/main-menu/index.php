<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.09.2018
 * Time: 17:52
 */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $widget \common\widgets\MainMenu\MainMenu */
/* @var $site array */
/* @var $navigation array */
/* @var $id_geo_city int */
/* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
?>
<?php
NavBar::begin([
    'brandLabel' => $site['annotation'],
    'brandUrl' => Url::to([Yii::$app->homeUrl]),
    'options' => $widget->optoinsNavBar,
]);
$items = [];
?>
<?php foreach ($navigation as $item): ?>
    <?php if ($item['alias'] == 'basket'): ?>
        <?php
        $items[] = [
            'label' => Yii::t('app', $item['name']) . '<span id="basket-product-count"> ' . $this->render('@frontend/views/templates/control/blocks/basket/_basket-product-count') . '</span>',
            'url' => Url::to(['/control/default/index', 'alias_menu_item' => $item['alias']]),
            'active' => Yii::$app->request->get('alias_menu_item') == $item['alias']
        ];
        ?>
        <?php
        $js = <<< JS
            $('#main-body-container').on('pjax:end',   function() { 
                $.pjax({
                    type: "GET", 
                    url: "/bm/update-count",
                    container: "#basket-product-count",
                    push: false,
                    timeout: 20000,
                    scrollTo: false
                });
            });
JS;
        $this->registerJs($js);
        ?>
    <?php elseif (Yii::$app->hasModule($item['alias'])): ?>
        <?php if ($item['alias'] == 'login'): ?>
            <?php /* Если "Войти" */ ?>
            <?php
            $items[] = [
                'label' => Yii::t('app', $item['name']),
                'url' => false,
                'options' => [
                    'class' => 'cursor-pointer',
                    'onclick' => '
                        $.pjax({
                            type: "POST",
                            url: "'.Url::to(['/' . $item['alias'] . '/default/index']).'",
                            container: "#pjaxModalUniversal",
                            push: false,
                            scrollTo: false
                        })'
                ]
            ];
            ?>
        <?php elseif ($item['alias'] == 'signup'): ?>
            <?php
            $items[] = [
                'label' => Yii::t('app', $item['name']),
                'url' => false,
                'options' => [
                    'class' => 'cursor-pointer',
                    'onclick' => '
                        $.pjax({
                            type: "POST",
                            url: "'.Url::to(['/' . $item['alias'] . '/default/index']).'",
                            container: "#pjaxModalUniversal",
                            push: false,
                            scrollTo: false
                        })'
                ]
            ];
            ?>
        <?php elseif ($item['alias'] == 'geo'): ?>
            <?php
            $cityName = $fieldsManage->getCityName($id_geo_city);
            $items[] = [
                'label' => $cityName ? $cityName : Yii::t('app', $item['name']),
                'url' => false,
                'options' => [
                    'class' => 'cursor-pointer',
                    'onclick' => '
                        $.pjax({
                            type: "POST",
                            url: "'.Url::to(['/' . $item['alias'] . '/default/index', 'lang' => Yii::$app->language]).'",
                            container: "#pjaxModalUniversal",
                            push: false,
                            scrollTo: false
                        })'
                ]
            ];
            ?>
        <?php else: ?>
            <?php
            $items[] = [
                'label' => Yii::t('app', $item['name']),
                'url' => Url::to(['/' . $item['alias'] . '/default/index']),
                'active' => Yii::$app->controller->module->id == $item['alias']
            ];
            ?>
        <?php endif; ?>
    <?php elseif ($item['alias'] == 'i18n'): ?>
        <?php
        $items[] = $widget->langMenu;
        ?>
    <?php elseif ($item['alias'] != 'main'): ?>
        <?php
        $items[] = [
            'label' => Yii::t('app', $item['name']),
            'url' => Url::to(['/control/default/index', 'alias_menu_item' => $item['alias']]),
            'active' => Yii::$app->request->get('alias_menu_item') == $item['alias']
        ];
        ?>
    <?php endif; ?>
<?php endforeach; ?>
<?= Nav::widget([
    'options' => $widget->optoinsNav,
    'items' => $items,
    'encodeLabels' => false
]);
NavBar::end();
?>
