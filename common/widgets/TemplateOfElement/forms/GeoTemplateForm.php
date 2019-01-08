<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 08.01.2019
 * Time: 6:27
 */

namespace common\widgets\TemplateOfElement\forms;

use Yii;
use common\models\Constants;
use common\models\extend\TemplateExtend;
use common\models\forms\TemplateForm;
use common\components\validators\DocumentArrayValidator;
use yii\helpers\Json;

class GeoTemplateForm extends TemplateForm
{
    public $elements_fields = [];
    public $errors_fields = [];

    public $value_int;
    public $value_number;
    public $value_string;
    public $value_array;

    public $input_date;
    public $input_date_from;
    public $input_date_to;

    public $id_geo_country;
    public $id_geo_region;
    public $id_geo_city;

    public $field_error;

    public $file;
    public $few_files;

    public function rules()
    {
        $items = TemplateExtend::rules();
        $items[] = ['elements_fields', DocumentArrayValidator::class];
        $items[] = [['errors_fields', 'value_array'], 'each', 'rule' => ['integer'], 'on' => ['create-element', 'update-element']];
        $items[] = ['value_int', 'integer'];

        return $items;
    }

    public function beforeValidate()
    {
        parent::beforeValidate();
        $this->validateFields();
        return true;
    }

    /**
     * Валидация полей шаблона
     *
     * @return boolean
     */
    public function validateFields() {

        // Проверка на обязательное заполнение
        foreach ($this->elements_fields as $key => $forms_field) {
            $field = (new \yii\db\Query())
                ->select(['*'])
                ->from('field')
                ->where(['id' => $key])
                ->one();

            if ($field['is_required'] && is_array($forms_field)) {
                foreach ($forms_field as $sub_key => $item) {
                    if (!$item) {
                        // если не найдено элемента
                        $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" обязательно для заполнения.', ['field' => $field['name']]);
                    }
                }
            }
        }

        if ($this->errors_fields) {
            $this->addError('field_error', 'Ошибка поля шаблона');
            return false;
        }
        return true;
    }
}