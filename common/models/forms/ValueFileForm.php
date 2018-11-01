<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 25.10.2018
 * Time: 10:38
 */

namespace common\models\forms;

use Yii;
use common\models\extend\ValueFileExtend;

class ValueFileForm extends ValueFileExtend
{
    public function beforeDelete()
    {
        parent::beforeDelete();
        if (file_exists(Yii::getAlias('@frontend/web' . $this->path)))
            unlink(Yii::getAlias('@frontend/web' . $this->path));

        return true;
    }
}