<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.02.2019
 * Time: 16:23
 */

use yii\bootstrap\Carousel;
use frontend\views\templates\control\blocks\carousel\assets\CarouselAsset;

/* @var $this yii\web\View */
/* @var $items */

CarouselAsset::register($this);
?>
<?= Carousel::widget([
    'items' => $items,
    'options' => [
        'id' => 'block-carousel',
        'class' => '',
        'style' => 'width:100%;',
        'data-interval' => 'false'
    ],
    'controls' => [
        '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span>',
        '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span>'
    ],     // Стрелочки вперед - назад
    'showIndicators' => true,                   // отображать индикаторы (кругляшки)
]);
?>
<ul id="autoWidth" class="cs-hidden light-slider" style="margin-top: 10px;">
    <?php
    $i = 0;
    foreach ($items as $item): ?>
        <li onclick="$('#block-carousel').carousel(<?= $i ?>);"><?= $item ?></li>
        <?php
        $i++;
    endforeach; ?>
</ul>
<?php
$js = <<< JS
$(document).ready(function(){
    $('#block-carousel').carousel({interval: false});
});
JS;
$this->registerJs($js);