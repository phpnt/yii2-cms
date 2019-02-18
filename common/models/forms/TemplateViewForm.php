<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 13.02.2019
 * Time: 21:02
 */

namespace common\models\forms;

use common\models\extend\TemplateViewExtend;

class TemplateViewForm extends TemplateViewExtend
{
    public function beforeValidate()
    {
        parent::beforeValidate();

        return true;
    }
}