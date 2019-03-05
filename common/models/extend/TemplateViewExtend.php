<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 13.02.2019
 * Time: 21:02
 */

namespace common\models\extend;

use Yii;
use common\models\forms\TemplateForm;
use common\models\TemplateView;
use yii\helpers\Json;

/**
 * @property string $haveTemplateFields
 * @property array $useTemplateFieldsTypes
 * @property boolean $haveTemplateType
 *
 * @property TemplateForm $template
 */
class TemplateViewExtend extends TemplateView
{
    /**
     * Возвращает используется ли тип
     *
     * @return boolean
     */
    public function getHaveTemplateType($types, $type)
    {
        if ($types) {
            foreach ($types as $key => $value) {
                if ($value == $type) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Возвращает используемые типы шаблона
     *
     * @return array
     */
    public function getUseTemplateFieldsTypes()
    {
        if ($this->template->fields) {

            $types = [];

            foreach ($this->template->fields as $field) {
                $types[] = $field->type;
            }
            return $types;
        }
        return [];
    }

    /**
     * Позвращает используемые поля шаблона, добавленные пользователем
     * @return string
     */
    public function getHaveTemplateFields()
    {
        $colOne = '';
        $colTwo = '';
        if ($this->template->fields) {
            $fieldsNumber = count($this->template->fields);
            $haltOne = round($fieldsNumber/2);

            $fields = $this->template->fields;

            $i = 0;
            while ($i < $haltOne) {
                $colOne .= Yii::t('app', $fields[$i]->name) . '<br>';
                unset($fields[$i]);
                $i++;
            }

            while ($i < $fieldsNumber) {
                $colTwo .= Yii::t('app', $fields[$i]->name) . '<br>';
                unset($fields[$i]);
                $i++;
            }

            return '<div class="col-sm-6">' . $colOne . '</div>' . '<div class="col-sm-6">' . $colTwo . '</div>';
        }
        return Yii::t('app', 'Поля отсутствуют.');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(TemplateForm::class, ['id' => 'template_id']);
    }
}