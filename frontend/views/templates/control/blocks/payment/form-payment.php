<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 04.03.2019
 * Time: 23:32
 */

use common\widgets\Basket\PaymentSum;

/* @var $this yii\web\View */
?>
<div class="block-payment">
    <div class="col-xs-12">
        <h3 class="text-center m-b-md"><?= Yii::t('app', 'Форма оплаты') ?></h3>
        <h3 class="text-center m-b-md"><strong><?= Yii::t('app', 'К оплате') . ': ' ?></strong></h3>
        <?php $sums = new PaymentSum() ?>
        <?php foreach ($sums->getSums() as $currency => $sum): ?>
            <h2 class="text-center m-b-md text-success"><strong><?= Yii::$app->formatter->asCurrency($sum, $currency) ?></strong></h2>
        <?php endforeach; ?>
    </div>
</div>
