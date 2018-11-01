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

/* @var $this yii\web\View */
/* @var $page array информация о странице */
/* @var $dataItems array элементы страницы */
/* @var $fieldsManage \common\components\other\FieldsManage */
$fieldsManage = Yii::$app->fieldsManage;

$this->title = Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-default-index">
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']); ?>
    </div>
    <?php foreach ($dataItems as $dataItem): ?>
        <div class="col-md-6">
            <a href="<?= Url::to(['/' . $page['alias'] . '/default/view', 'alias' => $dataItem['alias']]) ?>" class="element-link">
                <div class="element-card">
                    <?= Yii::t('app', $dataItem['name']) ?><br>
                    <?= Yii::t('app', $dataItem['annotation']) ?><br>
                    <?= Yii::t('app', $dataItem['content']) ?><br>
                    <?php if ($page['template_id']): ?>
                        <?php $templateData = $fieldsManage->getData($dataItem['id'], $page['template_id']) ?>
                        <?php p($templateData) ?>
                    <?php endif; ?>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
