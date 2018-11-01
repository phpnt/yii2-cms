<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 18.10.2018
 * Time: 23:24
 */

namespace common\components\validators;

use Yii;
use yii\validators\Validator;

class DocumentArrayValidator extends Validator
{
    public function validateAttribute($modelDocumentForm, $attribute)
    {
        if($attribute && !is_array($modelDocumentForm->$attribute)){
            $this->addError($modelDocumentForm, $attribute, Yii::t('app', 'Дополнительные поля должны являться массивом.'));
        }
    }
}
