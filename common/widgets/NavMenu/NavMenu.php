<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 8:38
 */

namespace common\widgets\NavMenu;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class NavMenu extends Widget
{
    public $folder;
    public $document_id;

    public $optionsNav = ['class' => 'nav-pills'];
    public $optionsItems = ['class' => 'full-width'];
    public $linkOptions = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $data = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['parent_id' => $this->document_id])
            ->all();

        $items = [];
        foreach ($data as $item) {
            $items[] = [
                'label' => Yii::t('app', $item['name']),
                'url' => Url::to(['/' . $this->folder . '/default/view-list', 'folder' => $item['alias']]),
                'active' => (Yii::$app->request->get('folder') == $item['alias']),
                'options' => $this->optionsItems,
                'linkOptions' => $this->linkOptions
            ];
        }

        return $this->render('index', [
            'widget' => $this,
            'items' => $items
        ]);
    }
}