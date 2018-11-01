<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:28
 */

namespace common\models\forms;

use common\models\extend\TemplateExtend;

class TemplateForm extends TemplateExtend
{
    public function rules()
    {
        $items = TemplateExtend::rules();
        $items[] = [['name'], 'string', 'on' => ['create-template', 'update-template']];
        $items[] = [['name'], 'trim', 'on' => ['create-folder', 'update-folder', 'create-document', 'update-document']];

        return $items;
    }

    public function attributeLabels()
    {
        $items = TemplateExtend::attributeLabels();

        return $items;
    }
}