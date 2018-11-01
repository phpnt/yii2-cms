<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 28.10.2018
 * Time: 21:41
 */

/* @var $this \yii\web\View */
/* @var $widget \common\widgets\LangSwitch\LangSwitch */
?>
<li class="dropdown">
    <a class="dropdown-toggle" href="#" data-toggle="dropdown"><?= $widget->langMenu['label'] ?> <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <?php foreach ($widget->langMenu['items'] as $item): ?>
        <li><a href="<?= $item['url'] ?>" tabindex="-1"><?= $item['label'] ?></a></li>
        <?php endforeach; ?>
    </ul>
</li>