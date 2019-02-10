<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:24
 */

namespace common\models\forms;

use common\widgets\TemplateOfElement\components\FieldsManage;
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
    // информация о текущей странице
    public $alias_menu_item;    // алиас элемента главного меню
    public $alias_sidebar_item; // алиас элемента бокового меню
    public $alias_item;         // алиас элемента

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

        /* Если позиция не указана, ставим номер по умолчанию */
        if (!$this->position && $this->parent_id) {
            $this->position = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where(['parent_id' => $this->parent_id])
                ->count();
        } elseif ($this->position && $this->oldAttributes['position'] != $this->position && $this->parent_id) {
            /* Ранее предшествующий элемент */
            $beforeItem = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'position' => $this->oldAttributes['position'] - 1,
                    'parent_id' => $this->parent_id
                ])
                ->one();

            // если позиция изменена
            if ($beforeItem['id'] != $this->position) {
                /* Будущий предшествующий элемент */
                $beforeItem = (new \yii\db\Query())
                    ->select(['*'])
                    ->from('document')
                    ->where([
                        'id' => $this->position,
                        'parent_id' => $this->parent_id
                    ])
                    ->one();

                $items = (new \yii\db\Query())
                    ->select(['id', 'position'])
                    ->from('document')
                    ->where([
                        'parent_id' => $this->parent_id,
                    ])
                    ->orderBy(['position' => SORT_ASC])
                    ->all();

                // Удаляем текущий элемент из списка
                foreach ($items as $key => $item) {
                    if ($item['id'] == $this->id) {
                        unset($items[$key]);
                    }
                }

                $i = 0;
                // находим предыдущий элемент и после него текущий
                $db = Yii::$app->db;
                foreach ($items as $item) {
                    $item['position'] = $i;
                    if ($item['id'] == $beforeItem['id']) {
                        $db->createCommand()->update('document', ['position' => $i], 'id='.$item['id'])->execute();
                        $i++;
                        $this->position = $i;
                    } else {
                        $db->createCommand()->update('document', ['position' => $i], 'id='.$item['id'])->execute();
                    }
                    $i++;
                }
            } else {
                $this->position = $beforeItem['position'] + 1;
            }
        }

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

        /* Удаление не используемых изображений в редакторе Summernote */
        $images = (new \yii\db\Query())
            ->select(['*'])
            ->from('value_file')
            ->where([
                'title' => Yii::$app->user->id . '-annotation',
                'document_id' => null,
            ])
            ->orWhere([
                'title' => Yii::$app->user->id . '-annotation',
                'document_id' => $this->id
            ])
            ->all();

        $lostFiles = [];
        foreach ($images as $image) {
            $result = strstr($this->annotation, $image['path']);
            if ($result) {
                $modelImageForm = ImageForm::findOne($image['id']);
                $modelImageForm->document_id = $this->id;
                if (!$modelImageForm->save()) {
                    dd($modelImageForm->errors);
                }
            } else {
                $lostFiles[] = [
                    'id' => $image['id'],
                    'path' => $image['path'],
                ];
            }
        }

        /* Удаление не используемых изображений в редакторе Summernote */
        $images = (new \yii\db\Query())
            ->select(['*'])
            ->from('value_file')
            ->where([
                'title' => Yii::$app->user->id . '-content',
                'document_id' => null,
            ])
            ->orWhere([
                'title' => Yii::$app->user->id . '-content',
                'document_id' => $this->id])
            ->all();

        foreach ($images as $image) {
            $result = strstr($this->content, $image['path']);
            if ($result) {
                $modelImageForm = ImageForm::findOne($image['id']);
                $modelImageForm->document_id = $this->id;
                if (!$modelImageForm->save()) {
                    dd($modelImageForm->errors);
                }
            } else {
                $lostFiles[] = [
                    'id' => $image['id'],
                    'path' => $image['path'],
                ];
            }
        }

        $lostFilesIds = [];
        if ($lostFiles) {
            foreach ($lostFiles as $file) {
                if (file_exists(Yii::getAlias('@frontend/web' . $file['path'])))
                    unlink(Yii::getAlias('@frontend/web' . $file['path']));
                $lostFilesIds[] = $file['id'];
            }
            ImageForm::deleteAll(['id' => $lostFilesIds]);
        }
    }

    public function afterFind()
    {
        parent::afterFind();

        if (Yii::$app->controller->id != 'csv-manager' && $this->position) {
            /* Если позиция имеет какое-либо значение, определяем ID предыдущего элемента */
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'parent_id' => $this->parent_id,
                    'position' => $this->position - 1,
                ])
                ->one();
            $this->position = $data['id'];
        }
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
                        $this->errors_fields[$key][0] = Yii::t('app', $field['error_required'], ['name' => $field['name']]);
                    }
                }

                foreach ($forms_field as $sub_key => $item) {
                    if (is_string($item)) {
                        $item = trim($item);
                    }
                    if (!$item && $field['type'] != Constants::FIELD_TYPE_RADIO && $field['type'] != Constants::FIELD_TYPE_LIST) {
                        // для всех полей, кроме радио и списков
                        $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_required'], ['name' => $field['name']]);
                    } elseif ($field['type'] == Constants::FIELD_TYPE_RADIO || $field['type'] == Constants::FIELD_TYPE_LIST) {
                        if (isset($this->elements_fields[$key][$sub_key]) && $this->elements_fields[$key][$sub_key] == '') {
                            $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_required'], ['name' => $field['name']]);
                        }
                    }
                }
            }
        }

        foreach ($this->elements_fields as $key => $forms_field) {
            $field = (new \yii\db\Query())
                ->select(['*'])
                ->from('field')
                ->where(['id' => $key])
                ->one();

            if (is_array($forms_field)) {
                foreach ($forms_field as $sub_key => $item) {
                    if (is_string($this->elements_fields[$key][$sub_key])) {
                        $this->elements_fields[$key][$sub_key] = trim($this->elements_fields[$key][$sub_key]);
                    }
                    // ограничения для полей
                    if ($field['type'] == Constants::FIELD_TYPE_INT ||
                        $field['type'] == Constants::FIELD_TYPE_INT_RANGE) {
                        if (!$field['min_val'] || $field['min_val'] < -2147483648) {
                            $field['min_val'] = -2147483648;
                        }
                    }
                    // Проверка DOUBLE на число
                    if (($field['type'] == Constants::FIELD_TYPE_INT ||
                        $field['type'] == Constants::FIELD_TYPE_INT_RANGE ||
                        $field['type'] == Constants::FIELD_TYPE_FLOAT ||
                        $field['type'] == Constants::FIELD_TYPE_FLOAT_RANGE) &&
                        $this->elements_fields[$key][$sub_key] != '') {
                        if (!is_numeric($this->elements_fields[$key][$sub_key])) {
                            $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле не является числом.');
                        }
                    }
                    // проверка на минимальное числовое значение
                    if ($field['min_val'] && $this->elements_fields[$key][$sub_key] != '') {
                        if ($field['type'] == Constants::FIELD_TYPE_INT ||
                            $field['type'] == Constants::FIELD_TYPE_INT_RANGE ||
                            $field['type'] == Constants::FIELD_TYPE_FLOAT ||
                            $field['type'] == Constants::FIELD_TYPE_FLOAT_RANGE ||
                            $field['type'] == Constants::FIELD_TYPE_PRICE) {
                            if ($this->elements_fields[$key][$sub_key] < (int) $field['min_val']) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_value'], [
                                    'name' => $field['name'],
                                    'min_val' => $field['min_val'],
                                    'max_val' => $field['max_val'],
                                ]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_DATE ||
                            $field['type'] == Constants::FIELD_TYPE_DATE_RANGE) {
                            if (strtotime($this->elements_fields[$key][$sub_key]) < (int) $field['min_val']) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_value'], [
                                    'name' => $field['name'],
                                    'min_val' => Yii::$app->formatter->asDate($field['min_val']),
                                    'max_val' => Yii::$app->formatter->asDate($field['max_val']),
                                ]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_FILE ||
                            $field['type'] == Constants::FIELD_TYPE_FEW_FILES) {
                            foreach ($this->elements_fields[$key] as $file) {
                                /* @var $file yii\web\UploadedFile */
                                if ($file->size < (int) $field['min_val']) {
                                    $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_value'], [
                                        'name' => $field['name'],
                                        'min_val' => $field['min_val'],
                                        'max_val' => $field['max_val'],
                                    ]);
                                }
                            }
                        }
                    }
                    // ограничения для полей
                    if ($field['type'] == Constants::FIELD_TYPE_INT ||
                        $field['type'] == Constants::FIELD_TYPE_INT_RANGE) {
                        if (!$field['max_val'] || $field['max_val'] > 2147483647) {
                            $field['max_val'] = 2147483647;
                        }
                    }
                    // проверка на максимальное числовое значение
                    if ($field['max_val'] && $this->elements_fields[$key][$sub_key] != '') {
                        if ($field['type'] == Constants::FIELD_TYPE_INT ||
                            $field['type'] == Constants::FIELD_TYPE_INT_RANGE ||
                            $field['type'] == Constants::FIELD_TYPE_FLOAT ||
                            $field['type'] == Constants::FIELD_TYPE_FLOAT_RANGE ||
                            $field['type'] == Constants::FIELD_TYPE_PRICE) {
                            if ($this->elements_fields[$key][$sub_key] > (int) $field['max_val']) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_value'], [
                                    'name' => $field['name'],
                                    'min_val' => $field['min_val'],
                                    'max_val' => $field['max_val'],
                                ]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_DATE ||
                            $field['type'] == Constants::FIELD_TYPE_DATE_RANGE) {
                            if (strtotime($this->elements_fields[$key][$sub_key]) > (int) $field['max_val']) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_value'], [
                                    'name' => $field['name'],
                                    'min_val' => $field['min_val'] ? Yii::$app->formatter->asDate($field['min_val']) : Yii::t('app', '(не задано)'),
                                    'max_val' => $field['max_val'] ? Yii::$app->formatter->asDate($field['max_val']) : Yii::t('app', '(не задано)'),
                                ]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_FILE ||
                            $field['type'] == Constants::FIELD_TYPE_FEW_FILES) {
                            foreach ($this->elements_fields[$key] as $file) {
                                /* @var $file yii\web\UploadedFile */
                                if ($file->size > (int) $field['max_val']) {
                                    $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_value'], [
                                        'name' => $field['name'],
                                        'min_val' => $field['min_val'],
                                        'max_val' => $field['max_val'],
                                    ]);
                                }
                            }
                        }
                    }

                    // ограничения для полей
                    if ($field['type'] == Constants::FIELD_TYPE_INT ||
                        $field['type'] == Constants::FIELD_TYPE_INT_RANGE ||
                        $field['type'] == Constants::FIELD_TYPE_STRING) {
                        if (!$field['min_str'] || $field['min_str'] < 0) {
                            $field['min_str'] = 0;
                        }
                    }
                    // проверка на минимальное кол-во символов
                    if ($field['min_str'] && $this->elements_fields[$key][$sub_key] != '') {
                        if ($field['type'] == Constants::FIELD_TYPE_INT ||
                            $field['type'] == Constants::FIELD_TYPE_INT_RANGE ||
                            $field['type'] == Constants::FIELD_TYPE_FLOAT ||
                            $field['type'] == Constants::FIELD_TYPE_FLOAT_RANGE ||
                            $field['type'] == Constants::FIELD_TYPE_STRING ||
                            $field['type'] == Constants::FIELD_TYPE_PRICE) {
                            if (iconv_strlen($this->elements_fields[$key][$sub_key]) < (int) $field['min_str']) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_length'], [
                                    'name' => $field['name'],
                                    'min_str' => $field['min_str'],
                                    'max_str' => $field['max_str'],
                                ]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_FEW_FILES) {
                            if (count($this->elements_fields[$key]) < $field['min_str']) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_length'], [
                                    'name' => $field['name'],
                                    'min_str' => $field['min_str'],
                                    'max_str' => $field['max_str'],
                                ]);
                            }
                        }
                    }

                    // ограничения для полей
                    if ($field['type'] == Constants::FIELD_TYPE_INT ||
                        $field['type'] == Constants::FIELD_TYPE_INT_RANGE) {
                        if (!$field['max_str'] || $field['max_str'] > 10) {
                            $field['max_str'] = 10;
                        }
                    }
                    if ($field['type'] == Constants::FIELD_TYPE_STRING ||
                        $field['type'] == Constants::FIELD_TYPE_ADDRESS) {
                        if (!$field['max_str'] || $field['max_str'] > 255) {
                            $field['max_str'] = 255;
                        }
                    }

                    // проверка на максимальное количество символов
                    if ($field['max_str'] && $this->elements_fields[$key][$sub_key] != '') {
                        if ($field['type'] == Constants::FIELD_TYPE_INT ||
                            $field['type'] == Constants::FIELD_TYPE_INT_RANGE ||
                            $field['type'] == Constants::FIELD_TYPE_FLOAT ||
                            $field['type'] == Constants::FIELD_TYPE_FLOAT_RANGE ||
                            $field['type'] == Constants::FIELD_TYPE_STRING ||
                            $field['type'] == Constants::FIELD_TYPE_PRICE ||
                            $field['type'] == Constants::FIELD_TYPE_ADDRESS) {
                            if (iconv_strlen($this->elements_fields[$key][$sub_key]) > (int) $field['max_str']) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_length'], [
                                    'name' => $field['name'],
                                    'min_str' => $field['min_str'],
                                    'max_str' => $field['max_str'],
                                ]);
                            }
                        }
                        if ($field['type'] == Constants::FIELD_TYPE_FEW_FILES) {
                            if (count($this->elements_fields[$key]) > $field['max_str']) {
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_length'], [
                                    'name' => $field['name'],
                                    'min_str' => $field['min_str'],
                                    'max_str' => $field['max_str'],
                                ]);
                            }
                        }
                    }

                    // Проверка на email и url и youtube ссылки и др. валидаторов
                    if ($field['type'] == Constants::FIELD_TYPE_EMAIL) {
                        if (isset($this->elements_fields[$key][$sub_key]) && !filter_var($this->elements_fields[$key][$sub_key], FILTER_VALIDATE_EMAIL)) {
                            $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле не является email адресом.');
                        }
                    }
                    if ($field['type'] == Constants::FIELD_TYPE_URL ||
                        $field['type'] == Constants::FIELD_TYPE_SOCIAL) {
                        if (isset($this->elements_fields[$key][$sub_key]) && !filter_var($this->elements_fields[$key][$sub_key], FILTER_VALIDATE_URL)) {
                            $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле не является ссылкой.');
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
                                $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле не является YuoTube ссылкой.');
                            }
                        }
                    }
                    // валидация расширений файлов
                    if ($field['type'] == Constants::FIELD_TYPE_FILE ||
                        $field['type'] == Constants::FIELD_TYPE_FEW_FILES) {
                        $params = Json::decode($field['params']);
                        foreach ($this->elements_fields[$key] as $file) {
                            /* @var $file yii\web\UploadedFile */
                            if ($params['file_extensions'] && !in_array($file->extension, $params['file_extensions'])) {
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
                }
            }
        }

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
                                'value' => (int) $this->elements_fields[$key][$sub_key],
                            ])
                            ->one();
                    }
                    if ($field['type'] == Constants::FIELD_TYPE_FLOAT ||
                        $field['type'] == Constants::FIELD_TYPE_PRICE) {
                        $data = (new \yii\db\Query())
                            ->select(['*'])
                            ->from('value_numeric')
                            ->where([
                                'field_id' => $field['id'],
                                'value' => $this->elements_fields[$key][$sub_key],
                            ])
                            ->one();
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
                            ->one();
                    }
                    if ($field['type'] == Constants::FIELD_TYPE_TEXT) {
                        $data = (new \yii\db\Query())
                            ->select(['*'])
                            ->from('value_text')
                            ->where([
                                'field_id' => $field['id'],
                                'value' => $this->elements_fields[$key][$sub_key],
                            ])
                            ->one();
                    }

                    if ($data && $data['document_id'] != $this->id) {
                        $this->errors_fields[$key][$sub_key] = Yii::t('app', $field['error_unique'], ['name' => $field['name']]);
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

    /**
     * запись полей шаблона
     *
     * @return boolean
     * @throws ErrorException
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
            if ($field['type'] == Constants::FIELD_TYPE_INT_RANGE) {
                $fieldsManage->setIntRange($field, $forms_field, $this->id);
            }
            // запись значений для диапазона целых чисел
            if ($field['type'] == Constants::FIELD_TYPE_LIST_MULTY ||
                $field['type'] == Constants::FIELD_TYPE_CHECKBOX) {
                $fieldsManage->setMulty($field, $forms_field, $this->id);
            }
            // запись значений для дробей
            if ($field['type'] == Constants::FIELD_TYPE_FLOAT ||
                $field['type'] == Constants::FIELD_TYPE_PRICE) {
                $fieldsManage->setNum($field, $forms_field, $this->id);
            }
            // запись одной даты
            if ($field['type'] == Constants::FIELD_TYPE_DATE) {
                $forms_field[0] = strtotime($forms_field[0]);
                $fieldsManage->setInt($field, $forms_field, $this->id);
            }
            // запись значений для диапазона дат
            if ($field['type'] == Constants::FIELD_TYPE_DATE_RANGE) {
                $fieldsManage->setDataRange($field, $forms_field, $this->id);
            }
            // запись диапазона дат
            if ($field['type'] == Constants::FIELD_TYPE_DATE) {
                $fieldsManage->setInt($field, $forms_field, $this->id);
            }
            // запись значений для диапазона дробных чисел
            if ($field['type'] == Constants::FIELD_TYPE_FLOAT_RANGE) {
                $fieldsManage->setNumRange($field, $forms_field, $this->id);
            }
            // запись значений для строки
            if ($field['type'] == Constants::FIELD_TYPE_STRING ||
                $field['type'] == Constants::FIELD_TYPE_ADDRESS ||
                $field['type'] == Constants::FIELD_TYPE_EMAIL ||
                $field['type'] == Constants::FIELD_TYPE_URL ||
                $field['type'] == Constants::FIELD_TYPE_SOCIAL ||
                $field['type'] == Constants::FIELD_TYPE_YOUTUBE) {
                $fieldsManage->setStr($field, $forms_field, $this->id);
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
}