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

//dd(Yii::$app->hasModule('basket'));
?>
<?php
NavBar::begin([
    'brandLabel' => $site['annotation'],
    'brandUrl' => Yii::$app->homeUrl,
    'options' => $widget->optoinsNavBar,
]);
$items = [];

//dd($navigation);
//dd(Yii::$app->hasModule('mainsdf'));
?>
<?php foreach ($navigation as $item): ?>
    <?php if ($item['alias'] == 'basket'): ?>
        <?php
        $items[] = [
            'label' => Yii::t('app', $item['name']) . $this->render('_basket-product-count'),
            'url' => Url::to(['/basket/default/index']),
            'active' => Yii::$app->controller->module->id == $item['alias']
        ];
        ?>
    <?php elseif (Yii::$app->hasModule($item['alias'])): ?>
        <?php
        $items[] = [
            'label' => Yii::t('app', $item['name']),
            'url' => Url::to(['/' . $item['alias'] . '/default/index']),
            'active' => Yii::$app->controller->module->id == $item['alias']
        ];
        ?>
    <?php else: ?>
        <?php
        $items[] = [
            'label' => Yii::t('app', $item['name']),
            'url' => Url::to(['/control/default/index', 'alias' => $item['alias']]),
            'active' => Yii::$app->request->get('alias') == $item['alias']
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
