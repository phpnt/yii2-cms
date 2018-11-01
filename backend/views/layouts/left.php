<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

use yii\helpers\Url;
use yii\widgets\Menu;

/* @var $this yii\web\View */ 
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <?= Menu::widget(
            [
                'options' => [
                    'class' => 'sidebar-menu tree',
                    'data-widget' => "tree"
                ],
                'submenuTemplate' => "\n<ul class='treeview-menu' role='menu'>\n{items}\n</ul>\n",
                'encodeLabels' => false,
                'items' => [
                    [
                        'label' => Yii::t('app', 'Панель администрирования'),
                        'options' => ['class' => 'header'],
                    ],
                    [
                        'label' => '<i class="glyphicon glyphicon-home"></i><span> ' . Yii::t('app', 'Рабочий стол') . '</span>',
                        'url' => ['/main/manage/index'],
                        'options' => ['class' => 'treeview'],
                        'visible' => Yii::$app->user->can('main/manage/index')
                    ],
                    [
                        'label' => '<i class="glyphicon glyphicon-file"></i><span> ' . Yii::t('app', 'Документы') . '</span>',
                        'url' => ['/document/manage/index'],
                        'options' => ['class' => 'treeview'],
                        'visible' => Yii::$app->user->can('document/manage/index')
                    ],
                    [
                        'label' => '<i class="fas fa-shopping-basket"></i><span> ' . Yii::t('app', 'Корзина') . '</span>',
                        'url' => ['/document/basket/index'],
                        'options' => ['class' => 'treeview'],
                        'visible' => Yii::$app->user->can('document/basket/index')
                    ],
                    [
                        'label' => '<i class="fa fa-user"></i><span> '.Yii::t('app', 'Пользователи').'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => '<i class="fa fa-users"></i><span> '.Yii::t('app', 'Пользователи').'</span>',
                                'url' => ['/user/manage/index'],
                                'options' => ['class' => 'treeview'],
                                'visible' => Yii::$app->user->can('user/manage/index')
                            ],
                            [
                                'label' => '<i class="fa fa-user-lock"></i><span> '.Yii::t('app', 'Роли и права').'</span>',
                                'url' => ['/role/manage/index'],
                                'options' => ['class' => 'treeview'],
                                'visible' => Yii::$app->user->can('role/manage/index')
                            ],
                        ],
                        'active' => Yii::$app->controller->module->id == 'user' || Yii::$app->controller->module->id == 'role',
                    ],
                    [
                        'label' => '<i class="fa fa-map"></i><span> '.Yii::t('app', 'Гео').'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => '<i class="fa fa-globe-asia"></i><span> '.Yii::t('app', 'Страны').'</span>',
                                'url' => ['/geo/country/index'],
                                'options' => ['class' => 'treeview'],
                                'visible' => Yii::$app->user->can('geo/country/index')
                            ],
                            [
                                'label' => '<i class="fa fa-map"></i><span> '.Yii::t('app', 'Регионы').'</span>',
                                'url' => ['/geo/region/index'],
                                'options' => ['class' => 'treeview'],
                                'visible' => Yii::$app->user->can('geo/region/index')
                            ],
                            [
                                'label' => '<i class="fa fa-building"></i><span> '.Yii::t('app', 'Города').'</span>',
                                'url' => ['/geo/city/index'],
                                'options' => ['class' => 'treeview'],
                                'visible' => Yii::$app->user->can('geo/city/index')
                            ],
                        ],
                        'active' => Yii::$app->controller->module->id == 'geo'
                    ],
                    [
                        'label' => '<i class="fas fa-language"></i><span> ' . Yii::t('app', 'I18n') . '</span>',
                        'url' => ['/i18n/manage/index'],
                        'options' => ['class' => 'treeview'],
                        'visible' => Yii::$app->user->can('i18n/manage/index')
                    ],
                    [
                        'label' => '<i class="glyphicon glyphicon-cog"></i><span> '.Yii::t('app', 'Настройки').'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => '<i class="fa fa-chart-area"></i><span> '.Yii::t('app', 'Статистика').'</span>',
                                'url' => ['/settings/statistic/index'],
                                'options' => ['class' => 'treeview'],
                                'visible' => Yii::$app->user->can('settings/statistic/index')
                            ],
                            [
                                'label' => '<i class="fa fa-download"></i><span> '.Yii::t('app', 'Экспорт всей БД в CSV').'</span>',
                                'url' => Url::to(['/csv-manager/export',
                                    'models[0]' => \common\models\search\UserSearch::class,
                                    'models[1]' => \common\models\search\AuthAssignmentSearch::class,
                                    'models[2]' => \common\models\search\AuthItemSearch::class,
                                    'models[3]' => \common\models\search\AuthItemChildSearch::class,
                                    'models[4]' => \common\models\search\AuthRuleSearch::class,
                                    'models[5]' => \common\models\search\DocumentSearch::class,
                                    'models[6]' => \common\models\search\FieldSearch::class,
                                    'models[7]' => \common\models\search\GeoCountrySearch::class,
                                    'models[8]' => \common\models\search\GeoRegionSearch::class,
                                    'models[9]' => \common\models\search\GeoCitySearch::class,
                                    'models[10]' => \common\models\search\LikeSearch::class,
                                    'models[11]' => \common\models\search\TemplateSearch::class,
                                    'models[12]' => \common\models\search\UserOauthKeySearch::class,
                                    'models[13]' => \common\models\search\ValueTextSearch::class,
                                    'models[14]' => \common\models\search\ValueStringSearch::class,
                                    'models[15]' => \common\models\search\ValueNumericSearch::class,
                                    'models[16]' => \common\models\search\ValueFileSearch::class,
                                    'models[17]' => \common\models\search\ValueIntSearch::class,
                                    'models[18]' => \common\models\search\VisitSearch::class,
                                    'models[19]' => \common\models\search\MessageSearch::class,
                                    'models[20]' => \common\models\search\SourceMessageSearch::class,
                                    'models[21]' => \common\models\search\BasketSearch::class,
                                    'with_header' => true
                                ]),
                                'options' => ['class' => 'treeview'],
                                'visible' => Yii::$app->user->can('admin')
                            ],
                        ],
                        'active' => Yii::$app->controller->module->id == 'settings',
                    ],
                    [
                        'label' => '<i class="glyphicon glyphicon-hdd"></i><span> ' . Yii::t('app', 'Файловый менеджер') . '</span>',
                        'url' => ['#'],
                        'template' => '<a id="link-files-manager" href="javascript:void(0);" class="url-class">{label}</a>',
                        'options' => ['class' => 'treeview']
                    ],
                ],
            ]
        ) ?>

    </section>
</aside>
<?php
$url = Url::to(['/main/manage/files-manager']);
$js = <<< JS
$('#link-files-manager').click(function() {
    $.pjax({
        type: "GET",
        url: "$url",
        container: "#pjaxModalUniversal",
        push: false,
        timeout: 10000,
        scrollTo: false
    });
});
JS;
$this->registerJs($js);