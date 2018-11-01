<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:24
 */

namespace common\models\forms;

use common\components\other\FieldsManage;
use common\components\validators\DocumentArrayValidator;
use common\components\validators\DocumentNameValidator;
use common\components\validators\DocumentRouteValidator;
use common\models\Constants;
use Yii;
use common\models\extend\DocumentExtend;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\StaleObjectException;
use yii\helpers\Json;
use yii\web\UploadedFile;

class DocumentForm extends DocumentExtend
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
        $items = DocumentExtend::rules();
        $items[] = [['status'], 'required', 'on' => ['create-folder', 'update-folder', 'create-element', 'update-element']];
        $items[] = [['name', 'alias'], 'trim', 'on' => ['create-folder', 'update-folder', 'create-element', 'update-element']];
        $items[] = ['name', DocumentNameValidator::className()];
        $items[] = ['alias', 'unique'];
        $items[] = ['route', DocumentRouteValidator::className()];
        $items[] = ['elements_fields', DocumentArrayValidator::className()];
        $items[] = [['errors_fields', 'value_array'], 'each', 'rule' => ['integer'], 'on' => ['create-element', 'update-element']];
        $items[] = ['value_int', 'integer', 'on' => ['create-element', 'update-element']];
        $items[] = [['value_number'], 'filter', 'filter' => 'floatval', 'on' => ['create-element', 'update-element']];
        $items[] = [['value_number'], 'number', 'on' => ['create-element', 'update-element']];
        $items[] = [['value_string', 'input_date', 'input_date_from', 'input_date_to', 'field_error'], 'string', 'on' => ['create-element', 'update-element']];
        $items[] = [['file'], 'file', 'skipOnEmpty' => true, 'on' => ['create-element', 'update-element']];
        $items[] = [['few_files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 20, 'on' => ['create-element', 'update-element']];

        return $items;
    }

    public function attributeLabels()
    {
        $items = DocumentExtend::attributeLabels();

        return $items;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::className(),
        ];
    }

    public function beforeValidate()
    {
        parent::beforeValidate();

        if ($this->scenario != 'default') {
            if ($this->isNewRecord) {
                $this->created_by = Yii::$app->user->id;
                $this->updated_by = Yii::$app->user->id;
            } else {
                $this->updated_by = Yii::$app->user->id;
            }
            // валидация полей шаблона
            if (($this->scenario == 'create-element' || $this->scenario == 'update-element') && isset($this->template)) {
                $this->validateFields();
            }
        }

        return true;
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        if ($this->access == null) {
            $this->access = Constants::ACCESS_USER;
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // запись полей шаблона
        if (($this->scenario == 'create-element' || $this->scenario == 'update-element') && isset($this->template)) {
            $this->saveFields();
        }
    }

    /**
     * запись полей шаблона
     *
     * @return boolean
     */
    public function saveFields() {
        /* @var $fieldsManage FieldsManage */
        $fieldsManage = Yii::$app->fieldsManage;

        $this->getInstances();
        foreach ($this->elements_fields as $key => $forms_field) {
            // поиск поля
            $field = (new \yii\db\Query())
                ->select(['*'])
                ->from('field')
                ->where(['id' => $key])
                ->one();
            // запись значений для целых чисел
            if ($field['type'] == Constants::FIELD_TYPE_INT ||
                $field['type'] == Constants::FIELD_TYPE_RADIO ||
                $field['type'] == Constants::FIELD_TYPE_LIST ||
                $field['type'] == Constants::FIELD_TYPE_CITY ||
                $field['type'] == Constants::FIELD_TYPE_REGION ||
                $field['type'] == Constants::FIELD_TYPE_COUNTRY) {
                $fieldsManage->setInt($field, $forms_field, $this->id);
            }
            // запись значений для диапазона целых чисел
            if ($field['type'] == Constants::FIELD_TYPE_INT_RANGE ||
                $field['type'] == Constants::FIELD_TYPE_CHECKBOX) {
                $fieldsManage->setIntRange($field, $forms_field, $this->id);
            }
            // запись значений для диапазона целых чисел
            if ($field['type'] == Constants::FIELD_TYPE_LIST_MULTY) {
                $fieldsManage->setMulty($field, $forms_field, $this->id);
            }
            // запись значений для дробей
            if ($field['type'] == Constants::FIELD_TYPE_FLOAT ||
                $field['type'] == Constants::FIELD_TYPE_PRICE) {
                $fieldsManage->setNum($field, $forms_field, $this->id);
            }
            // запись значений для диапазона дробных чисел
            if ($field['type'] == Constants::FIELD_TYPE_FLOAT_RANGE) {
                $fieldsManage->setNumRange($field, $forms_field, $this->id);
            }
            // запись значений для строки
            if ($field['type'] == Constants::FIELD_TYPE_STRING ||
                $field['type'] == Constants::FIELD_TYPE_DATE ||
                $field['type'] == Constants::FIELD_TYPE_ADDRESS ||
                $field['type'] == Constants::FIELD_TYPE_EMAIL ||
                $field['type'] == Constants::FIELD_TYPE_URL ||
                $field['type'] == Constants::FIELD_TYPE_SOCIAL ||
                $field['type'] == Constants::FIELD_TYPE_YOUTUBE) {
                $fieldsManage->setStr($field, $forms_field, $this->id);
            }
            // запись значений для диапазона дат
            if ($field['type'] == Constants::FIELD_TYPE_DATE_RANGE) {
                $fieldsManage->setDataRange($field, $forms_field, $this->id);
            }
            // запись значений для текста
            if ($field['type'] == Constants::FIELD_TYPE_TEXT) {
                $fieldsManage->setText($field, $forms_field, $this->id);
            }
            // запись значений для текста
            if ($field['type'] == Constants::FIELD_TYPE_FILE) {
                $fieldsManage->setFile($field, $forms_field, $this->id);
            }
            // запись значений для текста
            if ($field['type'] == Constants::FIELD_TYPE_FEW_FILES) {
                $fieldsManage->setFewFile($field, $forms_field, $this->id);
            }
        }

        return true;
    }

    /**
     * @return boolean
     * @throws ErrorException
     */
    public function beforeDelete()
    {
        parent::beforeDelete();

        if (isset($this->valueNumerics)) {
            foreach ($this->valueNumerics as $modelValueNumericForm) {
                /* @var $modelValueNumericForm ValueNumericForm */
                try {
                    $modelValueNumericForm->delete();
                } catch (StaleObjectException $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                } catch (\Throwable $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                }
            }
        }

        if (isset($this->valueInts)) {
            foreach ($this->valueInts as $modelValueIntForm) {
                /* @var $modelValueIntForm ValueIntForm */
                try {
                    $modelValueIntForm->delete();
                } catch (StaleObjectException $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                } catch (\Throwable $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                }
            }
        }

        if (isset($this->valueStrings)) {
            foreach ($this->valueStrings as $modelValueStringForm) {
                /* @var $modelValueStringForm ValueStringForm */
                try {
                    $modelValueStringForm->delete();
                } catch (StaleObjectException $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                } catch (\Throwable $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                }
            }
        }

        if (isset($this->valueTexts)) {
            foreach ($this->valueTexts as $modelValueTextForm) {
                /* @var $modelValueTextForm ValueTextForm */
                try {
                    $modelValueTextForm->delete();
                } catch (StaleObjectException $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                } catch (\Throwable $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                }
            }
        }

        if (isset($this->valueFiles)) {
            foreach ($this->valueFiles as $modelValueFileForm) {
                /* @var $modelValueFileForm ValueFileForm */
                try {
                    $modelValueFileForm->delete();
                } catch (StaleObjectException $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                } catch (\Throwable $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                }
            }
        }

        if (isset($this->childs) && $this->childs) {
            foreach ($this->childs as $child) {
                try {
                    $child->delete();
                } catch (StaleObjectException $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                } catch (\Throwable $e) {
                    Yii::$app->errorHandler->logException($e);
                    throw new ErrorException($e->getMessage());
                }
            }
        }

        return true;
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
                            $errors = false;
                            foreach ($this->elements_fields[$key] as $file) {
                                /* @var $file yii\web\UploadedFile */
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
            $this->addError('field_error', 'Ошибка поля шаблона');
            return false;
        }

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