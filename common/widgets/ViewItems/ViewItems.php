<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 05.12.2018
 * Time: 20:13
 */

namespace common\widgets\ViewItems;

use common\models\search\DocumentSearch;
use Yii;
use common\models\Constants;
use yii\base\Widget;
use yii\helpers\Url;

class ViewItems extends Widget
{
    public $alias_menu_item;    // алиас элемента главного меню
    public $alias_sidebar_item; // алиас элемента бокового меню

    public $modelDocumentForm;  // выбранный элемент

    private $menu_item;
    private $sidebar_item;

    public $itemContainerClass = 'col-md-4';
    public $menuContainerClass = 'col-md-3';
    public $itemsMenuContainerClass = 'col-md-9';

    public $optionsNav = ['class' => 'nav-pills'];
    public $optionsItems = ['class' => 'full-width'];
    public $linkOptions = [];

    protected $tree = [];        // дерево элемента

    public $clearGeoCache = true;  // очищать кеш Geo после обновления поиска

    public function init()
    {
        parent::init();

        if ($this->clearGeoCache) {
            $this->clearCache();
        }

        $this->menu_item = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => $this->alias_menu_item,
            ])
            ->one();

        if ($this->alias_sidebar_item) {
            $this->sidebar_item = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'alias' => $this->alias_sidebar_item,
                ])
                ->one();
        }

        if ($this->modelDocumentForm) {
            // если выбран элемент
            $parent = $this->modelDocumentForm->parent_id;
            for ($i = 0; $i < 10; $i++) {
                $data = (new \yii\db\Query())
                    ->select(['*'])
                    ->from('document')
                    ->where([
                        'id' => $parent,
                    ])
                    ->one();

                if ($data) {
                    $this->tree[] = $data;
                    $parent = $data['parent_id'];
                }
            }
        } elseif ($this->alias_sidebar_item) {
            // если нажата ссылка бокового меню
            $parent = $this->sidebar_item['parent_id'];
            for ($i = 0; $i < 10; $i++) {
                $data = (new \yii\db\Query())
                    ->select(['*'])
                    ->from('document')
                    ->where([
                        'id' => $parent,
                    ])
                    ->one();

                if ($data) {
                    $this->tree[] = $data;
                    $parent = $data['parent_id'];
                }
            }
        } else {
            // если нажата ссылка главного меню
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'id' => $this->menu_item['parent_id'],
                ])
                ->one();

            if ($data) {
                $this->tree[] = $data;
            }
        }
        array_pop($this->tree);
        $this->tree = array_reverse($this->tree);
    }

    public function run()
    {
        if ($this->alias_sidebar_item) {
            // если нажата ссылка бокового меню
            $modelSearch = new DocumentSearch();
            $modelSearch->template_id = $this->sidebar_item['template_id'];
            $modelSearch->parent_id = $this->sidebar_item['id'];
            $modelSearch->status = Constants::STATUS_DOC_ACTIVE;
            $dataProvider = $modelSearch->searchElement(Yii::$app->request->queryParams);
        } else {
            // если нажата ссылка главного меню или выбран элемент
            $modelSearch = new DocumentSearch();
            $modelSearch->status = Constants::STATUS_DOC_ACTIVE;
            if ($this->alias_menu_item == 'basket') {
                $modelSearch->status = Constants::STATUS_DOC_ACTIVE;
                if (Yii::$app->user->isGuest) {
                    $items = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('document')
                        ->andWhere([
                            'parent_id' => $this->menu_item['id'],
                            'ip' => Yii::$app->request->userIP,
                            'user_agent' => Yii::$app->request->userAgent,
                        ])
                        ->all();
                    $products = [];
                    foreach ($items as $item) {
                        $products[] = $item['child_id'];
                    }
                } else {
                    $modelSearch->status = Constants::STATUS_DOC_ACTIVE;
                    $items = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('document')
                        ->andWhere([
                            'parent_id' => $this->menu_item['id'],
                            'created_by' => Yii::$app->user->id
                        ])
                        ->all();
                    $products = [];
                    foreach ($items as $item) {
                        $products[] = $item['child_id'];
                    }
                }

                $dataProvider = $modelSearch->searchBasketElements(Yii::$app->request->queryParams, $products);
                $modelSearch->template_id = $this->menu_item['template_id'];
                $modelSearch->parent_id = $this->menu_item['id'];
            } else {
                $modelSearch->template_id = $this->menu_item['template_id'];
                $modelSearch->parent_id = $this->menu_item['id'];
                $dataProvider = $modelSearch->searchElement(Yii::$app->request->queryParams);
            }
        }

        // получает элементы бокового меню
        $sidebar_items = $this->getChildFolders($this->menu_item['id']);
        $itemsMenu = [];

        if (!$sidebar_items && !$this->alias_sidebar_item && !$this->modelDocumentForm) {
            // если нет бокового меню, не выбрана ссылка бокового меню и не выбран элемент
            return $this->render('@frontend/views/templates/control/views/index', [
                'page' => $this->menu_item,
                'modelSearch' => $modelSearch,
                'dataProvider' => $dataProvider,
                'itemsMenu' => $itemsMenu ? $itemsMenu : false,
                'modelDocumentForm' => $this->modelDocumentForm,
                'tree' => $this->tree,
            ]);
        };

        /* Если нет бокового меню и выбран элемент отображаем только элемент */
        if (!$sidebar_items && !$this->alias_sidebar_item && $this->modelDocumentForm) {
            return $this->render('@frontend/views/templates/control/views/index', [
                'page' => $this->menu_item,
                'modelSearch' => $modelSearch,
                'dataProvider' => $dataProvider,
                'itemsMenu' => $itemsMenu ? $itemsMenu : false,
                'modelDocumentForm' => $this->modelDocumentForm,
                'tree' => $this->tree,
            ]);
        };

        /* Формируем меню */
        foreach ($sidebar_items as $sidebar_item) {
            $class = '';
            if ($sidebar_item['id'] == $this->sidebar_item['id']) {
                $class = 'active';
            } elseif ($sidebar_item['id'] == $this->sidebar_item['parent_id']) {
                $class = 'mm-active';
            }
            $itemsMenu[$sidebar_item['id']] = [
                'label' => Yii::t('app', $sidebar_item['name']),
                'url' => Url::to(['/control/default/view-list', 'alias_menu_item' => $this->menu_item['alias'], 'alias_sidebar_item' => $sidebar_item['alias']]),
                'options' => [
                    'class' => $class,
                ],
            ];
            $in_1_folders = $this->getChildFolders($sidebar_item['id']);

            if ($in_1_folders) {
                $itemsMenu[$sidebar_item['id']] += [
                    'options' => [
                        'class' => 'dropdown'
                    ],
                    'template' => '<a href="{url}" class="has-arrow">{label}</a>',
                ];

                foreach ($in_1_folders as $in_1_folder) {
                    $in_2_folders = $this->getChildFolders($in_1_folder['id']);

                    if ($in_2_folders) {
                        $class = '';
                        if ($in_1_folder['id'] == $this->sidebar_item['parent_id']) {
                            $class = 'mm-active';
                        };
                        $itemsMenu[$sidebar_item['id']]['items'][$in_1_folder['id']] = [
                            'label' => Yii::t('app', $in_1_folder['name']),
                            'url' => ['#'],
                        ];
                        foreach ($in_2_folders as $in_2_folder) {
                            $itemsMenu[$sidebar_item['id']]['items'][$in_1_folder['id']] += [
                                'options' => [
                                    'class' => 'dropdown '.$class
                                ],
                                'template' => '<a href="{url}" class="has-arrow">{label}</a>',
                            ];

                            $class = '';
                            if ($in_2_folder['id'] == $this->sidebar_item['id']) {
                                $class = 'active';
                            };
                            $itemsMenu[$sidebar_item['id']]['items'][$in_1_folder['id']]['items'][$in_2_folder['id']] = [
                                'label' => Yii::t('app', $in_2_folder['name']),
                                'url' => Url::to(['/control/default/view-list', 'alias_menu_item' => $this->menu_item['alias'], 'alias_sidebar_item' => $in_2_folder['alias']]),
                                'options' => [
                                    'class' => $class,
                                ],
                            ];
                        }
                    } else {
                        $class = '';
                        if ($in_1_folder['id'] == $this->sidebar_item['id']) {
                            $class = 'active';
                        };
                        $itemsMenu[$sidebar_item['id']]['items'][$in_1_folder['id']] = [
                            'label' => Yii::t('app', $in_1_folder['name']),
                            'url' => Url::to(['/control/default/view-list', 'alias_menu_item' => $this->menu_item['alias'], 'alias_sidebar_item' => $in_1_folder['alias']]),
                            'options' => [
                                'class' => $class,
                            ],
                        ];
                    }
                }
            }
        }

        /* Если нет бокового меню и выбран элемент отображаем только элемент */
        if ($this->sidebar_item && $this->modelDocumentForm) {
            return $this->render('@frontend/views/templates/control/views/index', [
                'page' => $this->menu_item,
                'modelSearch' => $modelSearch,
                'dataProvider' => $dataProvider,
                'itemsMenu' => $itemsMenu ? $itemsMenu : false,
                'modelDocumentForm' => $this->modelDocumentForm,
                'tree' => $this->tree,
            ]);
        };

        return $this->render('@frontend/views/templates/control/views/index', [
            'page' => $this->menu_item,
            'modelSearch' => $modelSearch,
            'dataProvider' => $dataProvider,
            'itemsMenu' => $itemsMenu ? $itemsMenu : false,
            'modelDocumentForm' => $this->modelDocumentForm,
            'tree' => $this->tree,
        ]);
    }

    private function getChildFolders($parentFolder) {
        return (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'parent_id' => $parentFolder,
                'status' => Constants::STATUS_DOC_ACTIVE,
            ])
            ->andWhere(['is not', 'is_folder', null])
            ->orderBy(['position' => SORT_ASC])
            ->all();
    }

    public function clearCache() {
        $session = Yii::$app->session;
        $cookiesResponse = Yii::$app->response->cookies;
        $cookiesRequest = Yii::$app->request->cookies;

        $session->remove('id_geo_country_search');
        if (isset($cookiesRequest['id_geo_country_search'])) {
            $cookiesResponse->remove('id_geo_country_search');
        }
        $session->remove('id_geo_region_search');
        if (isset($cookiesRequest['id_geo_region_search'])) {
            $cookiesResponse->remove('id_geo_region_search');
        }
        $session->remove('id_geo_city_search');
        if (isset($cookiesRequest['id_geo_city_search'])) {
            $cookiesResponse->remove('id_geo_city_search');
        }
    }
}