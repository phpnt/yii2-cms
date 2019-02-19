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

        $this->view = trim($this->view);

        if (!$this->view || $this->view == "") {
            $this->addError('view', 'Обязательно для заполнения.');
            return false;
        }

        return true;
    }
}