<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:28
 */

namespace common\models\forms;

use Yii;
use common\models\Constants;
use common\models\extend\TemplateExtend;
use yii\web\UploadedFile;

class TemplateForm extends TemplateExtend
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
        $items[] = [['name'], 'string', 'on' => ['create-template', 'update-template']];
        $items[] = [['name'], 'trim', 'on' => ['create-folder', 'update-folder', 'create-document', 'update-document']];

        return $items;
    }

    public function attributeLabels()
    {
        $items = TemplateExtend::attributeLabels();

        return $items;
    }

    /**
     * валидация полей шаблона
     *
     * @return boolean
     */
    public function validateFields() {
        $this->getInstances();

        // Проверка на обязательное заполнение
        foreach ($this->elements_fields as $key => $forms_field) {
            $field = (new \yii\db\Query())
                ->select(['*'])
                ->from('field')
                ->where(['id' => $key])
                ->one();

            if ($field['is_required'] && is_array($forms_field)) {

                if ($field['type'] == Constants::FIELD_TYPE_FILE || $field['type'] == Constants::FIELD_TYPE_FEW_FILES) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_file')
                        ->where([
                            'field_id' => $field['id'],
                            'document_id' => $this->id
                        ])
                        ->one();
                    if (!$forms_field && !$data) {
                        $this->errors_fields[$key][0] = Yii::t('app', 'Поле \"{field}\" обязательно для заполнения.', ['field' => $field['name']]);
                    }
                }

                foreach ($forms_field as $sub_key => $item) {
                    if (!$item && $field['type'] != Constants::FIELD_TYPE_RADIO && $field['type'] != Constants::FIELD_TYPE_LIST) {
                        // для всех полей, кроме радио и списков
                        $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" обязательно для заполнения.', ['field' => $field['name']]);
                    } elseif ($field['type'] == Constants::FIELD_TYPE_RADIO || $field['type'] == Constants::FIELD_TYPE_LIST) {
                        if (isset($this->elements_fields[$key][$sub_key]) && $this->elements_fields[$key][$sub_key] == '') {
                            $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" обязательно для заполнения.', ['field' => $field['name']]);
                        }
                    }
                }
            }
        }

        if (!$this->errors_fields) {
            // Проверка на email и url и youtube ссылки и др. валидаторов
            foreach ($this->elements_fields as $key => $forms_field) {
                $field = (new \yii\db\Query())
                    ->select(['*'])
                    ->from('field')
                    ->where(['id' => $key])
                    ->one();

                if (is_array($forms_field)) {
                    foreach ($forms_field as $sub_key => $item) {
                        if ($field['type'] == Constants::FIELD_TYPE_EMAIL) {
                            if (isset($this->elements_fields[$key][$sub_key]) && !filter_var($this->elements_fields[$key][$sub_key], FILTER_VALIDATE_EMAIL)) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" не является email адресом.', ['field' => $field['name']]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_URL ||
                            $field['type'] == Constants::FIELD_TYPE_SOCIAL) {
                            if (isset($this->elements_fields[$key][$sub_key]) && !filter_var($this->elements_fields[$key][$sub_key], FILTER_VALIDATE_URL)) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" не является ссылкой.', ['field' => $field['name']]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_YOUTUBE) {
                            if (isset($this->elements_fields[$key][$sub_key])) {
                                $rx = '~
                            ^(?:https?://)?                         # Optional protocol
                           (?:www[.])?                              # Optional sub-domain
                           (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
                           ([^&]{11})                               # Video id of 11 characters as capture group 1
                            ~x';

                                $is_youtube = preg_match($rx, $this->elements_fields[$key][$sub_key], $matches);

                                if (!$is_youtube) {
                                    $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" не является YuoTube ссылкой.', ['field' => $field['name']]);
                                }
                            }
                        }
                        // валидация расширений файлов
                        if ($field['type'] == Constants::FIELD_TYPE_FILE ||
                            $field['type'] == Constants::FIELD_TYPE_FEW_FILES) {
                            $params = Json::decode($field['params']);
                            foreach ($this->elements_fields[$key] as $file) {
                                /* @var $file UploadedFile */
                                if ($params['file_extensions'] && !array_search($file->extension, $params['file_extensions'])) {
                                    $ext_str = '';
                                    $i = 0;
                                    foreach ($params['file_extensions'] as $extension) {
                                        if ($i == 0) {
                                            $ext_str .= $extension;
                                        } else {
                                            $ext_str .= ', ' . $extension;
                                        }
                                        $i++;
                                    }
                                    $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Файлы могут иметь следующие расширения {extentions}', ['extentions' => $ext_str]);
                                }
                            }
                        }
                        // проверка на минимальное значение
                        if ($field['min']) {
                            if ($field['type'] == Constants::FIELD_TYPE_INT ||
                                $field['type'] == Constants::FIELD_TYPE_INT_RANGE ||
                                $field['type'] == Constants::FIELD_TYPE_FLOAT ||
                                $field['type'] == Constants::FIELD_TYPE_FLOAT_RANGE) {
                                if ($this->elements_fields[$key][$sub_key] < $field['min']) {
                                    $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" не может быть меньше \"{min}\".', ['field' => $field['name'], 'min' => $field['min']]);
                                }
                            }
                            if ($field['type'] == Constants::FIELD_TYPE_DATE ||
                                $field['type'] == Constants::FIELD_TYPE_DATE_RANGE) {
                                if (strtotime($this->elements_fields[$key][$sub_key]) < $field['min']) {
                                    $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" не может быть меньше \"{date}\".', ['field' => $field['name'], 'date' => Yii::$app->formatter->asDate($field['min'])]);
                                }
                            }
                        }
                        // проверка на максимальное значение
                        if ($field['max']) {
                            if ($field['type'] == Constants::FIELD_TYPE_INT ||
                                $field['type'] == Constants::FIELD_TYPE_INT_RANGE ||
                                $field['type'] == Constants::FIELD_TYPE_FLOAT ||
                                $field['type'] == Constants::FIELD_TYPE_FLOAT_RANGE) {
                                if ($this->elements_fields[$key][$sub_key] > $field['max']) {
                                    $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" не может быть больше \"{max}\".', ['field' => $field['name'], 'max' => $field['max']]);
                                }
                            }
                            if ($field['type'] == Constants::FIELD_TYPE_STRING) {
                                if (iconv_strlen($this->elements_fields[$key][$sub_key]) > $field['max']) {
                                    $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" должно содержать не более \"{strlen}\" символов.', ['field' => $field['name'], 'strlen' => $field['max']]);
                                }
                            }
                            if ($field['type'] == Constants::FIELD_TYPE_DATE ||
                                $field['type'] == Constants::FIELD_TYPE_DATE_RANGE) {
                                if (strtotime($this->elements_fields[$key][$sub_key]) > $field['max']) {
                                    $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" не может быть больше \"{date}\".', ['field' => $field['name'], 'date' => Yii::$app->formatter->asDate($field['max'])]);
                                }
                            }
                            if ($field['type'] == Constants::FIELD_TYPE_FEW_FILES) {
                                if (count($this->elements_fields[$key]) > $field['max']) {
                                    $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" не может содержать больше \"{files}\" файлов.', ['field' => $field['name'], 'files' => $field['max']]);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!$this->errors_fields) {
            // Проверка на уникальность
            foreach ($this->elements_fields as $key => $forms_field) {
                $field = (new \yii\db\Query())
                    ->select(['*'])
                    ->from('field')
                    ->where(['id' => $key])
                    ->one();

                if ($field['is_unique'] && is_array($forms_field)) {
                    foreach ($forms_field as $sub_key => $item) {
                        if ($field['type'] == Constants::FIELD_TYPE_INT) {
                            $data = (new \yii\db\Query())
                                ->select(['*'])
                                ->from('value_int')
                                ->where([
                                    'field_id' => $field['id'],
                                    'value' => $this->elements_fields[$key][$sub_key],
                                ])
                                ->andWhere(['!=', 'document_id', $this->id])
                                ->one();

                            if ($data) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" должно быть уникальным.', ['field' => $field['name']]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_FLOAT) {
                            $data = (new \yii\db\Query())
                                ->select(['*'])
                                ->from('value_numeric')
                                ->where([
                                    'field_id' => $field['id'],
                                    'value' => $this->elements_fields[$key][$sub_key],
                                ])
                                ->andWhere(['!=', 'document_id', $this->id])
                                ->one();

                            if ($data) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" должно быть уникальным.', ['field' => $field['name']]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_STRING ||
                            $field['type'] == Constants::FIELD_TYPE_EMAIL ||
                            $field['type'] == Constants::FIELD_TYPE_URL ||
                            $field['type'] == Constants::FIELD_TYPE_SOCIAL ||
                            $field['type'] == Constants::FIELD_TYPE_YOUTUBE) {
                            $data = (new \yii\db\Query())
                                ->select(['*'])
                                ->from('value_string')
                                ->where([
                                    'field_id' => $field['id'],
                                    'value' => $this->elements_fields[$key][$sub_key],
                                ])
                                ->andWhere(['!=', 'document_id', $this->id])
                                ->one();

                            if ($data) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" должно быть уникальным.', ['field' => $field['name']]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_TEXT) {
                            $data = (new \yii\db\Query())
                                ->select(['*'])
                                ->from('value_text')
                                ->where([
                                    'field_id' => $field['id'],
                                    'value' => $this->elements_fields[$key][$sub_key],
                                ])
                                ->andWhere(['!=', 'document_id', $this->id])
                                ->one();

                            if ($data) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле \"{field}\" должно быть уникальным.', ['field' => $field['name']]);
                            }
                        }
                    }
                }
            }
        }

        if ($this->errors_fields) {
            pd($this->errors_fields);
            $this->addError('field_error', 'Ошибка поля шаблона');
            return false;
        }

        pd($this);

        return true;
    }

    private function getInstances() {
        $file_fields = (new \yii\db\Query())
            ->select(['*'])
            ->from('field')
            ->where([
                'template_id' => $this->template_id,
                'type' => [
                    Constants::FIELD_TYPE_FILE,
                    Constants::FIELD_TYPE_FEW_FILES
                ]
            ])
            ->all();

        foreach ($file_fields as $field) {
            switch ($field['type']) {
                case Constants::FIELD_TYPE_FILE:
                    $this->elements_fields[$field['id']] = UploadedFile::getInstances($this, 'file');
                    break;
                case Constants::FIELD_TYPE_FEW_FILES:
                    $this->elements_fields[$field['id']] = UploadedFile::getInstances($this, 'few_files');
                    break;
            }
        }
    }
}