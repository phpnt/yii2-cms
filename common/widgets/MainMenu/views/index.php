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
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;
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
            'label' => Yii::t('app', $item['name']) . $this->render('_basket-product-count'),
            'url' => Url::to(['/basket/default/index']),
            'active' => Yii::$app->controller->module->id == $item['alias']
        ];
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
            $cityName = $fieldsManage->getCityName();
            $items[] = [
                'label' => $cityName ? $cityName : Yii::t('app', $item['name']),
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
        <?php else: ?>
            <?php
            $items[] = [
                'label' => Yii::t('app', $item['name']),
                'url' => Url::to(['/' . $item['alias'] . '/default/index']),
                'active' => Yii::$app->controller->module->id == $item['alias']
            ];
            ?>
        <?php endif; ?>
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
