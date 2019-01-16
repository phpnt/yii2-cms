<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 15:06
 */

use common\widgets\Basket\CountUserProducts;
use phpnt\bootstrapNotify\BootstrapNotify;
?>
<span id="basket-product-count">
    <?= BootstrapNotify::widget([]) ?>
    <span class="badge badge-danger"><?= CountUserProducts::widget() ?></span>
</span>
