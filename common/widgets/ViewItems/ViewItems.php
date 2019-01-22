<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 05.12.2018
 * Time: 20:13
 */

namespace common\widgets\ViewItems;

use Yii;
use common\models\Constants;
use yii\base\Widget;
use yii\helpers\Url;

class ViewItems extends Widget
{
    public $page;
    public $template;
    public $parent;
    public $item;

    public $itemContainerClass = 'col-md-4';
    public $menuContainerClass = 'col-md-3';
    public $itemsMenuContainerClass = 'col-md-9';

    public $optionsNav = ['class' => 'nav-pills'];
    public $optionsItems = ['class' => 'full-width'];
    public $linkOptions = [];

    protected $tree = [];        // дерево элемента

    public function init()
    {
        parent::init();

        // находим дерево элемента
        if ($this->item) {
            $parent = $this->item['parent_id'];
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
        } elseif ($this->parent) {
            $parent = $this->parent['parent_id'];
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
            $parent = $this->page['parent_id'];
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
        }
        array_pop($this->tree);
        $this->tree = array_reverse($this->tree);
    }

    public function run()
    {
        if ($this->parent) {
            $items = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'parent_id' => $this->parent['id'],
                    'status' => Constants::STATUS_DOC_ACTIVE,
                    'is_folder' => null
                ])
                ->all();
        } else {
            $items = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'parent_id' => $this->page['id'],
                    'status' => Constants::STATUS_DOC_ACTIVE,
                    'is_folder' => null
                ])
                ->all();
        }

        $folders = $this->getChildFolders($this->page['id']);
        $itemsMenu = [];

        /* Если нет папок отображаем только элементы */
        if (!$folders && !$this->parent && !$this->item) {
            return $this->render('@frontend/views/templates/control/views/index', [
                'page' => $this->page,
                'template' => $this->template,
                'parent' => false,
                'itemsMenu' => false,
                'item' => false,
                'items' => $items ? $items : false,
                'tree' => $this->tree,
            ]);
        };

        /* Если нет папок и выбран элемент отображаем только элемент */
        if (!$folders && $this->parent && $this->item) {
            return $this->render('@frontend/views/templates/control/views/index', [
                'page' => $this->page,
                'template' => $this->template,
                'parent' => $this->parent,
                'itemsMenu' => false,
                'item' => $this->item,
                'items' => false,
                'tree' => $this->tree,
            ]);
        };

        /* Формируем меню */
        foreach ($folders as $folder) {
            $class = '';
            if ($folder['id'] == $this->parent['id']) {
                $class = 'active';
            } elseif ($folder['id'] == $this->parent['parent_id']) {
                $class = 'mm-active';
            }
            $itemsMenu[$folder['id']] = [
                'label' => Yii::t('app', $folder['name']),
                'url' => Url::to(['/control/default/view-list', 'alias' => $this->page['alias'], 'folder_alias' => $folder['alias']]),
                'options' => [
                    'class' => $class,
                ],
            ];
            $in_1_folders = $this->getChildFolders($folder['id']);

            if ($in_1_folders) {
                $itemsMenu[$folder['id']] += [
                    'options' => [
                        'class' => 'dropdown'
                    ],
                    'template' => '<a href="{url}" class="has-arrow">{label}</a>',
                ];

                foreach ($in_1_folders as $in_1_folder) {
                    $in_2_folders = $this->getChildFolders($in_1_folder['id']);

                    if ($in_2_folders) {
                        $class = '';
                        if ($in_1_folder['id'] == $this->parent['parent_id']) {
                            $class = 'mm-active';
                        };
                        $itemsMenu[$folder['id']]['items'][$in_1_folder['id']] = [
                            'label' => Yii::t('app', $in_1_folder['name']),
                            'url' => ['#'],
                        ];
                        foreach ($in_2_folders as $in_2_folder) {
                            $itemsMenu[$folder['id']]['items'][$in_1_folder['id']] += [
                                'options' => [
                                    'class' => 'dropdown '.$class
                                ],
                                'template' => '<a href="{url}" class="has-arrow">{label}</a>',
                            ];

                            $class = '';
                            if ($in_2_folder['id'] == $this->parent['id']) {
                                $class = 'active';
                            };
                            $itemsMenu[$folder['id']]['items'][$in_1_folder['id']]['items'][$in_2_folder['id']] = [
                                'label' => Yii::t('app', $in_2_folder['name']),
                                'url' => Url::to(['/control/default/view-list', 'alias' => $this->page['alias'], 'folder_alias' => $in_2_folder['alias']]),
                                'options' => [
                                    'class' => $class,
                                ],
                            ];
                        }
                    } else {
                        $class = '';
                        if ($in_1_folder['id'] == $this->parent['id']) {
                            $class = 'active';
                        };
                        $itemsMenu[$folder['id']]['items'][$in_1_folder['id']] = [
                            'label' => Yii::t('app', $in_1_folder['name']),
                            'url' => Url::to(['/control/default/view-list', 'alias' => $this->page['alias'], 'folder_alias' => $in_1_folder['alias']]),
                            'options' => [
                                'class' => $class,
                            ],
                        ];
                    }
                }
            }
        }

        /* Если нет папок и выбран элемент отображаем только элемент */
        if ($this->parent && $this->item) {
            return $this->render('@frontend/views/templates/control/views/index', [
                'page' => $this->page,
                'template' => $this->template,
                'parent' => $this->parent ? $this->parent : false,
                'itemsMenu' => $itemsMenu,
                'item' => $this->item,
                'items' => false,
                'tree' => $this->tree,
            ]);
        };

        return $this->render('@frontend/views/templates/control/views/index', [
            'page' => $this->page,
            'template' => $this->template,
            'parent' => $this->parent ? $this->parent : false,
            'itemsMenu' => $itemsMenu,
            'item' => false,
            'items' => $items,
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
            ->all();
    }
}