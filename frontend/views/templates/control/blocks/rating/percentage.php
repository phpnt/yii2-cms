<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 20.01.2019
 * Time: 8:52
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $document_id int */
/* @var $percent_count int */
/* @var $stars_count int */
/* @var $star_cost int */
/* @var $votes_number int */
?>
<div class="block-rating">
    <div class="col-md-12 text-right">
        <?php $i = 1; ?>
        <?php while ($stars_number >= $i): ?>
            <?php
            /* Текущее значение звезды */
            $value = $star_cost * $i;
            /* Установка иконок в зависимости от рейтинга */
            if ($percent_count >= $value) {
                $name = '<i class="fas fa-star"></i>';
            } else {
                if ($percent_count > $value - $star_cost) {
                    $name = '<i class="fas fa-star-half-alt"></i>';
                } else {
                    $name = '<i class="far fa-star"></i>';
                }
            }
            ?>

            <?= Html::a($name, 'javascript:void(0);', [
                'class' => 'text-warning',
                'onclick' => '
            $.pjax({
                type: "POST",
                url: "' . Url::to(['/rating/set-percent', 'document_id' => $document_id, 'value' => $value]) . '",
                container: "#rating-widget",
                data: { stars_number : ' . $stars_number . ', star_cost : ' . $star_cost . ' },
                push: false,
                timeout: 10000,
                scrollTo: false
            })'
            ]); ?>
            <?php $i++; ?>
        <?php endwhile; ?>
        <strong><?= Yii::t('app', 'Голосов {votes_number}', ['votes_number' => $votes_number]) ?></strong>
    </div>
</div>
