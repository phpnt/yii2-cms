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

/* @var $this \yii\web\View */
/* @var $widget \common\widgets\MainMenu\MainMenu */
/* @var $site array */
/* @var $navigation array */
?>
<?php
NavBar::begin([
    'brandLabel' => $site['annotation'],
    'brandUrl' => Yii::$app->homeUrl,
    'options' => $widget->optoinsNavBar,
]);
$items = [];
?>
<?php foreach ($navigation as $item): ?>
    <?php if ($item['alias'] == 'basket'): ?>
        <?php
        $items[] = [
            'label' => Yii::t('app', $item['name']).$this->render('_basket-product-count'),
            'url' => '/' . $item['alias'],
            'active' => Yii::$app->controller->module->id == $item['alias']
        ];
        ?>
    <?php else: ?>
        <?php
        $items[] = [
            'label' => Yii::t('app', $item['name']),
            'url' => '/' . $item['alias'],
            'active' => Yii::$app->controller->module->id == $item['alias']
        ];
        ?>
    <?php endif; ?>
<?php endforeach;
if ($widget->langMenu) {
    $items[] = $widget->langMenu;
}
?>
<?= Nav::widget([
    'options' => $widget->optoinsNav,
    'items' => $items,
    'encodeLabels' => false
]);
NavBar::end();
?>
