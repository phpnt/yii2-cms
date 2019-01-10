<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 11.12.2018
 * Time: 11:24
 */

/* @var $this \yii\web\View */
/* @var $page array Главная страница меню */
/* @var $template array используемый шаблон для элементов */
/* @var $items array Элементы в родительской папке */

$this->title = Yii::t('app', $page['name']);

if ($page['alias'] != 'main') {
    $this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="items">
    <div class="col-md-12">
        <?php p($this->viewFile); ?>
    </div>
    <div class="col-md-12">
        <?= Yii::t('app', $page['content']); ?>
    </div>
    <div class="col-md-12">
        <div class="row">
            <?php foreach ($items as $item): ?>
                <?php if ($template['mark'] == 'default' || $template['mark'] == ''): ?>
                    <div class="col-md-4">
                        <div class="row">
                            <?= $this->render('default/_item-of-list', [
                                'page' => $page,
                                'item' => $item,
                            ]); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-12">
                        <?= Yii::t('app', 'Необходимо создать новое представление для элементов шаблона {temp}.', ['temp' => $template['mark']]); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>