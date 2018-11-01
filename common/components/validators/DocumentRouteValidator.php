<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.08.2018
 * Time: 14:28
 */

namespace common\components\validators;

use Yii;
use common\models\forms\DocumentForm;
use yii\validators\Validator;

class DocumentRouteValidator extends Validator
{
    public function validateAttribute($modelDocumentForm, $attribute)
    {
        /* @var $modelDocumentForm DocumentForm */
        /* @var $findDocumentForm DocumentForm */
        $findDocumentForm = DocumentForm::findOne(['route' => $modelDocumentForm->$attribute, 'parent_id' => $modelDocumentForm->parent_id]);

        if ($findDocumentForm && $findDocumentForm->id != $modelDocumentForm->id) {
            $this->addError($modelDocumentForm, $attribute, Yii::t('app', 'Этот маршрут уже используется в данной папке.'));
        }
    }
}