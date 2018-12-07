<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 05.12.2018
 * Time: 20:13
 */

namespace common\widgets\ViewItems;

use common\models\Constants;
use yii\base\Widget;
use yii\helpers\Url;

class ViewItems extends Widget
{
    public $page;
    public $selectedPage;
    public $selectedItem;

    public $itemContainerClass = 'col-md-4';
    public $menuContainerClass = 'col-md-3';
    public $itemsMenuContainerClass = 'col-md-9';

    public $optionsNav = ['class' => 'nav-pills'];
    public $optionsItems = ['class' => 'full-width'];
    public $linkOptions = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if ($this->selectedPage) {
            $items = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'parent_id' => $this->selectedPage['id'],
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

        /* Если нет папок отображаем только элементы */
        if (!$folders && !$this->selectedPage && !$this->selectedItem) {
            return $this->render('no_menu', [
                'items' => $items,
                'widget' => $this,
            ]);
        };

        /* Если нет папок и выбран элемент отображаем только элемент */
        if (!$folders && $this->selectedPage && $this->selectedItem) {
            return $this->render('no_menu_element', [
                'widget' => $this,
            ]);
        };

        /* Формируем меню */
        $itemsMenu = [];
        foreach ($folders as $folder) {
            $class = '';
            if ($folder['id'] == $this->selectedPage['id']) {
                $class = 'active';
            } elseif ($folder['id'] == $this->selectedPage['parent_id']) {
                $class = 'mm-active';
            }
            $itemsMenu[$folder['id']] = [
                'label' => $folder['name'],
                'url' => Url::to(['/' . $this->page['alias'] . '/default/view-list', 'folder' => $folder['alias']]),
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
                        if ($in_1_folder['id'] == $this->selectedPage['parent_id']) {
                            $class = 'mm-active';
                        };
                        $itemsMenu[$folder['id']]['items'][$in_1_folder['id']] = [
                            'label' => $in_1_folder['name'],
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
                            if ($in_2_folder['id'] == $this->selectedPage['id']) {
                                $class = 'active';
                            };
                            $itemsMenu[$folder['id']]['items'][$in_1_folder['id']]['items'][$in_2_folder['id']] = [
                                'label' => $in_2_folder['name'],
                                'url' => Url::to(['/' . $this->page['alias'] . '/default/view-list', 'folder' => $in_2_folder['alias']]),
                                'options' => [
                                    'class' => $class,
                                ],
                            ];
                        }
                    } else {
                        $class = '';
                        if ($in_1_folder['id'] == $this->selectedPage['id']) {
                            $class = 'active';
                        };
                        $itemsMenu[$folder['id']]['items'][$in_1_folder['id']] = [
                            'label' => $in_1_folder['name'],
                            'url' => Url::to(['/' . $this->page['alias'] . '/default/view-list', 'folder' => $in_1_folder['alias']]),
                            'options' => [
                                'class' => $class,
                            ],
                        ];
                    }
                }
            }
        }

        /* Если нет папок и выбран элемент отображаем только элемент */
        if ($this->selectedPage && $this->selectedItem) {
            return $this->render('with_menu_element', [
                'itemsMenu' => $itemsMenu,
                'widget' => $this,
            ]);
        };

        return $this->render('with_menu', [
            'items' => $items,
            'itemsMenu' => $itemsMenu,
            'widget' => $this,
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