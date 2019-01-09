<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

/* @var $this yii\web\View */
/* @var $page array */
/* @var $modelProfileTemplateForm \common\widgets\TemplateOfElement\forms\ProfileTemplateForm */
/* @var $manyProfiles array */
?>
<div class="profile-default-index">
    <?= $this->render('@frontend/views/templates/profile/index', [
        'page' => $page,
        'modelProfileTemplateForm' => $modelProfileTemplateForm,
        'manyProfiles' => $manyProfiles
    ]); ?>
</div>
