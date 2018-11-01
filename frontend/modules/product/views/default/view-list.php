<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 27.10.2018
 * Time: 8:57
 */

use yii\helpers\Url;
use common\widgets\NavMenu\NavMenu;

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $data array информация о списке */
/* @var $dataItems array элементы страницы */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

$this->title = Yii::t('app', $data['name']);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app',  $page['name']),
    'url' => Url::to(['/' . $page['alias'] . '/default/index'])
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-default-index">
    <div class="col-md-3 m-b-lg">
        <?= NavMenu::widget(['folder' => $page['alias'], 'document_id' => $page['id']]); ?>
    </div>
    <div class="col-md-9">
        <div class="row">
            <?php foreach ($dataItems as $dataItem): ?>
                <div class="col-md-6">
                    <a href="<?= Url::to(['/' . $page['alias'] . '/default/view', 'folder' => $data['alias'], 'alias' => $dataItem['alias']]) ?>" class="element-link">
                        <div class="element-card">
                            <?= Yii::t('app', $dataItem['name']) ?><br>
                            <?= Yii::t('app', $dataItem['annotation']) ?><br>
                            <?= Yii::t('app', $dataItem['content']) ?><br>
                            <?php if ($dataItem['template_id']): ?>
                                <?php $templateData = $fieldsManage->getData($dataItem['id'], $dataItem['template_id']) ?>
                                <?php p($templateData) ?>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>