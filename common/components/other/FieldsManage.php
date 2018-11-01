<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.10.2018
 * Time: 6:31
 */

namespace common\components\other;

use common\models\forms\ValueFileForm;
use Yii;
use common\models\Constants;
use common\models\forms\FieldForm;
use common\models\forms\TemplateForm;
use common\models\forms\ValueIntForm;
use common\models\forms\ValueNumericForm;
use common\models\forms\ValueStringForm;
use common\models\forms\ValueTextForm;
use yii\base\ErrorException;
use yii\base\Object;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;

class FieldsManage extends Object
{
    public function init()
    {
        parent::init();
    }

    // записывает поле с целым числом
    public function setInt($field, $forms_field, $document_id)
    {
        $modelValueIntForm = ValueIntForm::findOne([
            'field_id' => $field['id'],
            'document_id' => $document_id,
        ]);
        if (!$modelValueIntForm) {
            $modelValueIntForm = new ValueIntForm();
            $modelValueIntForm->type = $field['type'];
            $modelValueIntForm->document_id = $document_id;
            $modelValueIntForm->field_id = $field['id'];
        }
        $modelValueIntForm->title = $field['name'];
        $this->saveModel($forms_field, $modelValueIntForm);
    }

    // записывает поле с диапазоном целых чисел
    public function setIntRange($field, $forms_field, $document_id)
    {
        $manyValueIntForm = ValueIntForm::findAll([
            'field_id' => $field['id'],
            'document_id' => $document_id,
        ]);
        if (!$manyValueIntForm) {
            foreach ($forms_field as $value) {
                $modelValueIntForm = new ValueIntForm();
                $modelValueIntForm->type = $field['type'];
                $modelValueIntForm->document_id = $document_id;
                $modelValueIntForm->field_id = $field['id'];
                $modelValueIntForm->title = $field['name'];
                $modelValueIntForm->value = (int) $value;
                if (!$modelValueIntForm->save()) {
                    dd($modelValueIntForm->errors);
                }
            }
        } else {
            $i = 0;
            foreach ($manyValueIntForm as $modelValueIntForm) {
                /* @var $modelValueIntForm ValueIntForm */
                $modelValueIntForm->title = $field['name'];
                $modelValueIntForm->value = (int) $forms_field[$i];
                if (!$modelValueIntForm->save()) {
                    dd($modelValueIntForm->errors);
                }
                $i++;
            }
        }
    }

    // записывает поле с диапазоном целых чисел
    public function setMulty($field, $forms_field, $document_id)
    {
        ValueIntForm::deleteAll([
            'field_id' => $field['id'],
            'document_id' => $document_id,
        ]);
        foreach ($forms_field[0] as $value) {
            $modelValueIntForm = new ValueIntForm();
            $modelValueIntForm->type = $field['type'];
            $modelValueIntForm->document_id = $document_id;
            $modelValueIntForm->field_id = $field['id'];
            $modelValueIntForm->title = $field['name'];
            $modelValueIntForm->value = (int) $value;
            if (!$modelValueIntForm->save()) {
                dd($modelValueIntForm->errors);
            }
        }
    }

    // записывает поле с дробным числом
    public function setNum($field, $forms_field, $document_id)
    {
        $modelValueNumericForm = ValueNumericForm::findOne([
            'field_id' => $field['id'],
            'document_id' => $document_id,
        ]);
        if (!$modelValueNumericForm) {
            $modelValueNumericForm = new ValueNumericForm();
            $modelValueNumericForm->type = $field['type'];
            $modelValueNumericForm->document_id = $document_id;
            $modelValueNumericForm->field_id = $field['id'];
        }
        $modelValueNumericForm->title = $field['name'];
        $this->saveModel($forms_field, $modelValueNumericForm);
    }

    // записывает поле с диапазоном дробных чисел
    public function setNumRange($field, $forms_field, $document_id)
    {
        $manyValueNumericForm = ValueNumericForm::findAll([
            'field_id' => $field['id'],
            'document_id' => $document_id,
        ]);
        if (!$manyValueNumericForm) {
            foreach ($forms_field as $value) {
                $modelValueNumericForm = new ValueNumericForm();
                $modelValueNumericForm->type = $field['type'];
                $modelValueNumericForm->document_id = $document_id;
                $modelValueNumericForm->field_id = $field['id'];
                $modelValueNumericForm->title = $field['name'];
                $modelValueNumericForm->value = $value;
                if (!$modelValueNumericForm->save()) {
                    dd($modelValueNumericForm->errors);
                }
            }
        } else {
            $i = 0;
            foreach ($manyValueNumericForm as $modelValueNumericForm) {
                /* @var $modelValueNumericForm ValueNumericForm */
                $modelValueNumericForm->title = $field['name'];
                $modelValueNumericForm->value = $forms_field[$i];
                if (!$modelValueNumericForm->save()) {
                    dd($modelValueNumericForm->errors);
                }
                $i++;
            }
        }
    }

    // записывает поле со строкой
    public function setStr($field, $forms_field, $document_id)
    {
        $modelValueStringForm = ValueStringForm::findOne([
            'field_id' => $field['id'],
            'document_id' => $document_id,
        ]);
        if (!$modelValueStringForm) {
            $modelValueStringForm = new ValueStringForm();
            $modelValueStringForm->type = $field['type'];
            $modelValueStringForm->document_id = $document_id;
            $modelValueStringForm->field_id = $field['id'];
        }
        $modelValueStringForm->title = $field['name'];
        $this->saveModel($forms_field, $modelValueStringForm);
    }

    // записывает поле с диапазоном дат
    public function setDataRange($field, $forms_field, $document_id)
    {
        $manyValueStringForm = ValueStringForm::findAll([
            'field_id' => $field['id'],
            'document_id' => $document_id,
        ]);
        if (!$manyValueStringForm) {
            foreach ($forms_field as $value) {
                $modelValueStringForm = new ValueStringForm();
                $modelValueStringForm->type = $field['type'];
                $modelValueStringForm->document_id = $document_id;
                $modelValueStringForm->field_id = $field['id'];
                $modelValueStringForm->title = $field['name'];
                $modelValueStringForm->value = $value;
                if (!$modelValueStringForm->save()) {
                    dd($modelValueStringForm->errors);
                }
            }
        } else {
            $i = 0;
            foreach ($manyValueStringForm as $modelValueStringForm) {
                /* @var $modelValueStringForm ValueStringForm */
                $modelValueStringForm->title = $field['name'];
                $modelValueStringForm->value = $forms_field[$i];
                if (!$modelValueStringForm->save()) {
                    dd($modelValueStringForm->errors);
                }
                $i++;
            }
        }
    }

    // записывает поле с текстом
    public function setText($field, $forms_field, $document_id)
    {
        $modelValueTextForm = ValueTextForm::findOne([
            'field_id' => $field['id'],
            'document_id' => $document_id,
        ]);
        if (!$modelValueTextForm) {
            $modelValueTextForm = new ValueTextForm();
            $modelValueTextForm->type = $field['type'];
            $modelValueTextForm->document_id = $document_id;
            $modelValueTextForm->field_id = $field['id'];
        }
        $modelValueTextForm->title = $field['name'];
        $this->saveModel($forms_field, $modelValueTextForm);
    }

    /**
     * записывает поле с файлом
     *
     * @return string
     * @throws ErrorException
     */
    public function setFile($field, $forms_field, $document_id)
    {
        if ($forms_field) {
            $modelValueFileForm = ValueFileForm::findOne([
                'field_id' => $field['id'],
                'document_id' => $document_id,
            ]);

            if ($modelValueFileForm) {
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

            foreach ($forms_field as $file) {
                /* @var $file yii\web\UploadedFile */
                $directory_1 = Yii::$app->security->generateRandomString(2);
                $directory_2 = Yii::$app->security->generateRandomString(2);
                FileHelper::createDirectory(Yii::getAlias('@frontend/web/uploads/'.$directory_1, $mode = 777));
                FileHelper::createDirectory(Yii::getAlias('@frontend/web/uploads/'.$directory_1.'/'.$directory_2, $mode = 777));
                $path = '@frontend/web/uploads/'.$directory_1.'/'.$directory_2;
                $url = '/uploads/'.$directory_1.'/'.$directory_2 . '/' . $file->name;

                $file->saveAs(Yii::getAlias($path . '/' . $file->name, $mode = 777));

                $modelValueFileForm = new ValueFileForm();
                $modelValueFileForm->title = $field['name'];
                $modelValueFileForm->name = $file->name;
                $modelValueFileForm->extension = $file->extension;
                $modelValueFileForm->size = $file->size;
                $modelValueFileForm->path = $url;
                $modelValueFileForm->type = $field['type'];
                $modelValueFileForm->document_id = $document_id;
                $modelValueFileForm->field_id = $field['id'];
                if (!$modelValueFileForm->save()) {
                    dd($modelValueFileForm->errors);
                }
            }
        }
    }

    /**
     * записывает поле с файлом
     *
     * @return string
     * @throws ErrorException
     */
    public function setFewFile($field, $forms_field, $document_id)
    {
        if ($forms_field) {
            $manyValueFileForm = ValueFileForm::findAll([
                'field_id' => $field['id'],
                'document_id' => $document_id,
            ]);

            if ($manyValueFileForm) {
                foreach ($manyValueFileForm as $modelValueFileForm) {
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

            foreach ($forms_field as $file) {
                /* @var $file yii\web\UploadedFile */
                $directory_1 = Yii::$app->security->generateRandomString(2);
                $directory_2 = Yii::$app->security->generateRandomString(2);
                FileHelper::createDirectory(Yii::getAlias('@frontend/web/uploads/' . $directory_1, $mode = 777));
                FileHelper::createDirectory(Yii::getAlias('@frontend/web/uploads/' . $directory_1 . '/' . $directory_2, $mode = 777));
                $path = '@frontend/web/uploads/' . $directory_1 . '/' . $directory_2;
                $url = '/uploads/' . $directory_1 . '/' . $directory_2 . '/' . $file->name;

                $file->saveAs(Yii::getAlias($path . '/' . $file->name, $mode = 777));

                $modelValueFileForm = new ValueFileForm();
                $modelValueFileForm->title = $field['name'];
                $modelValueFileForm->name = $file->name;
                $modelValueFileForm->extension = $file->extension;
                $modelValueFileForm->size = $file->size;
                $modelValueFileForm->path = $url;
                $modelValueFileForm->type = $field['type'];
                $modelValueFileForm->document_id = $document_id;
                $modelValueFileForm->field_id = $field['id'];
                if (!$modelValueFileForm->save()) {
                    dd($modelValueFileForm->errors);
                }
            }
        }
    }


    // записывает модель
    private function saveModel($fields, $model) {
        foreach ($fields as $item) {
            $model->value = $item;
            if (!$model->save()) {
                dd($model->errors);
            }
        }
    }

    // получение значений
    public function getValue($field_id, $type, $document_id)
    {
        if ($type == Constants::FIELD_TYPE_INT ||
            $type == Constants::FIELD_TYPE_RADIO ||
            $type == Constants::FIELD_TYPE_LIST ||
            $type == Constants::FIELD_TYPE_CITY ||
            $type == Constants::FIELD_TYPE_REGION ||
            $type == Constants::FIELD_TYPE_COUNTRY) {
            return $this->getInt($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_INT_RANGE ||
            $type == Constants::FIELD_TYPE_CHECKBOX) {
            return $this->getIntRange($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_LIST_MULTY) {
            return $this->getIntRange($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_FLOAT ||
            $type == Constants::FIELD_TYPE_PRICE) {
            return $this->getNum($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_FLOAT_RANGE) {
            return $this->getNumRange($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_STRING ||
            $type == Constants::FIELD_TYPE_DATE ||
            $type == Constants::FIELD_TYPE_ADDRESS ||
            $type == Constants::FIELD_TYPE_EMAIL ||
            $type == Constants::FIELD_TYPE_URL ||
            $type == Constants::FIELD_TYPE_SOCIAL ||
            $type == Constants::FIELD_TYPE_YOUTUBE) {
            return $this->getStr($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_DATE_RANGE) {
            return $this->getDateRange($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_TEXT) {
            return $this->getText($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_FILE) {
            return $this->getFile($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_FEW_FILES) {
            return $this->getFewFiles($field_id, $document_id);
        }

        return null;
    }

    // получает поля и значения документа с шаблоном
    public function getData($document_id, $template_id)
    {
        $modelTemplateForm = TemplateForm::findOne($template_id);

        $result = [];

        if (isset($modelTemplateForm->fields)) {
            //d($modelTemplateForm->fields);
            $i = 0;
            foreach ($modelTemplateForm->fields as $modelFieldForm) {
                /* @var $modelFieldForm FieldForm */
                if ($modelFieldForm->type == Constants::FIELD_TYPE_INT) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_INT_RANGE) {
                    $dataRange = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->all();

                    if ($dataRange) {
                        $data = [];
                        $y = 0;
                        foreach ($dataRange as $item) {
                            $data['title'] = $item['title'];
                            $data['value'][$y] = $item['value'];
                            $y++;
                        }
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_CHECKBOX) {
                    $dataRange = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->all();
                    if ($dataRange) {
                        $fieldData = (new \yii\db\Query())
                            ->select(['*'])
                            ->from('value_string')
                            ->where([
                                'field_id' => $dataRange[0]['field_id']
                            ])
                            ->all();
                        $data = [];
                        $y = 0;
                        foreach ($fieldData as $item) {
                            $data['value'][$y]['title'] = $item['value'];
                            $y++;
                        }
                        $y = 0;
                        foreach ($dataRange as $item) {
                            $data['title'] = $item['title'];
                            $data['value'][$y]['value'] = $item['value'];
                            $y++;
                        }
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_LIST_MULTY) {
                    $dataRange = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->all();
                    if ($dataRange) {
                        $fieldData = (new \yii\db\Query())
                            ->select(['*'])
                            ->from('value_string')
                            ->where([
                                'field_id' => $dataRange[0]['field_id']
                            ])
                            ->all();
                        $listData = [];
                        foreach ($dataRange as $item) {
                            $listData[] = $fieldData[$item['value']];
                        }
                        $data = [];
                        $y = 0;
                        foreach ($listData as $item) {
                            $data['title'] = $item['title'];
                            $data['value'][$y] = $item['value'];
                            $y++;
                        }
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FLOAT ||
                    $modelFieldForm->type == Constants::FIELD_TYPE_PRICE) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_numeric')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FLOAT_RANGE) {
                     $dataRange = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_numeric')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->all();
                    if ($dataRange) {
                        $data = [];
                        $y = 0;
                        foreach ($dataRange as $item) {
                            $data['title'] = $item['title'];
                            $data['value'][$y] = $item['value'];
                            $y++;
                        }
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_STRING ||
                    $modelFieldForm->type == Constants::FIELD_TYPE_DATE ||
                    $modelFieldForm->type == Constants::FIELD_TYPE_ADDRESS ||
                    $modelFieldForm->type == Constants::FIELD_TYPE_EMAIL ||
                    $modelFieldForm->type == Constants::FIELD_TYPE_URL ||
                    $modelFieldForm->type == Constants::FIELD_TYPE_SOCIAL ||
                    $modelFieldForm->type == Constants::FIELD_TYPE_YOUTUBE) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_string')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_DATE_RANGE) {
                    $dataRange = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_string')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->all();
                    if ($dataRange) {
                        $data = [];
                        $y = 0;
                        foreach ($dataRange as $item) {
                            $data['title'] = $item['title'];
                            $data['value'][$y] = $item['value'];
                            $y++;
                        }
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_TEXT) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_text')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_RADIO ||
                    $modelFieldForm->type == Constants::FIELD_TYPE_LIST) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                    if ($data) {
                        $values = (new \yii\db\Query())
                            ->select(['value'])
                            ->from('value_string')
                            ->where([
                                'field_id' => $data['field_id'],
                            ])
                            ->all();
                        $data['value'] = $values[$data['value']]['value'];
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_CITY) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                    if ($data) {
                        $name = $this->getCity($data['value']);
                        $data['value'] = $name;
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_REGION) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                    if ($data) {
                        $name = $this->getRegion($data['value']);
                        $data['value'] = $name;
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_COUNTRY) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                    if ($data) {
                        $name = $this->getCountry($data['value']);
                        $data['value'] = $name;
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FILE) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_file')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                    if ($data) {
                        $data['value'] = $data['path'];
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FEW_FILES) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_file')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();

                    if ($data) {
                        $values = (new \yii\db\Query())
                            ->select(['*'])
                            ->from('value_file')
                            ->where([
                                'field_id' => $modelFieldForm->id,
                                'document_id' => $document_id,
                            ])
                            ->all();
                        $data['value'] = $values;
                    }
                }

                if (isset($data)) {
                    $result[$i] = [
                        'title' => $data['title'],
                        'value' => $data['value'],
                        'type' => $modelFieldForm->type,
                    ];
                    $i++;
                    unset($data);
                }

            }
        }

        return $result;
    }

    // получает значение целого
    public function getInt($field_id, $document_id)
    {
        $modelValueIntForm = ValueIntForm::findOne([
            'field_id' => $field_id,
            'document_id' => $document_id,
        ]);

        if ($modelValueIntForm) {
            return $modelValueIntForm->value;
        }
        return null;
    }

    // получает значение диапазона целых чисел и чекбоксов
    public function getIntRange($field_id, $document_id)
    {
        $manyValueIntForm = ValueIntForm::findAll([
            'field_id' => $field_id,
            'document_id' => $document_id,
        ]);

        if ($manyValueIntForm) {
            $result = [];
            foreach ($manyValueIntForm as $modelValueIntForm) {
                /* @var $modelValueIntForm ValueIntForm */
                $result[] = $modelValueIntForm->value;
            }
            return $result;
        }
        return null;
    }

    // получает значение дробного
    public function getNum($field_id, $document_id)
    {
        $modelValueNumericForm = ValueNumericForm::findOne([
            'field_id' => $field_id,
            'document_id' => $document_id,
        ]);
        if ($modelValueNumericForm) {
            return $modelValueNumericForm->value;
        }
        return null;
    }

    // получает значение диапазона целых чисел
    public function getNumRange($field_id, $document_id)
    {
        $manyValueNumericForm = ValueNumericForm::findAll([
            'field_id' => $field_id,
            'document_id' => $document_id,
        ]);

        if ($manyValueNumericForm) {
            $result = [];
            foreach ($manyValueNumericForm as $modelValueNumericForm) {
                /* @var $modelValueNumericForm ValueNumericForm */
                $result[] = $modelValueNumericForm->value;
            }
            return $result;
        }
        return null;
    }

    // получает значение строки
    public function getStr($field_id, $document_id)
    {
        $modelValueStringForm = ValueStringForm::findOne([
            'field_id' => $field_id,
            'document_id' => $document_id,
        ]);
        if ($modelValueStringForm) {
            return $modelValueStringForm->value;
        }
        return null;
    }

    // получает значение диапазона дат
    public function getDateRange($field_id, $document_id)
    {
        $manyValueNumericForm = ValueStringForm::findAll([
            'field_id' => $field_id,
            'document_id' => $document_id,
        ]);

        if ($manyValueNumericForm) {
            $result = [];
            foreach ($manyValueNumericForm as $modelValueStringForm) {
                /* @var $modelValueStringForm ValueStringForm */
                $result[] = $modelValueStringForm->value;
            }
            return $result;
        }
        return null;
    }

    // получает значение текста
    public function getText($field_id, $document_id)
    {
        $modelValueTextForm = ValueTextForm::findOne([
            'field_id' => $field_id,
            'document_id' => $document_id,
        ]);

        if ($modelValueTextForm) {
            return $modelValueTextForm->value;
        }
        return null;
    }

    // получает значение текста
    public function getFile($field_id, $document_id)
    {
        $modelValueFileForm = ValueFileForm::findOne([
            'field_id' => $field_id,
            'document_id' => $document_id,
        ]);

        if ($modelValueFileForm) {
            return $modelValueFileForm;
        }
        return null;
    }

    // получает значение текста
    public function getFewFiles($field_id, $document_id)
    {
        $manyValueFileForm = ValueFileForm::findAll([
            'field_id' => $field_id,
            'document_id' => $document_id,
        ]);

        if ($manyValueFileForm) {
            return $manyValueFileForm;
        }
        return null;
    }

    // получает название города
    public function getCity($id_geo_city = null)
    {
        if ($id_geo_city) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_city')
                ->where([
                    'id_geo_city' => $id_geo_city,
                ])
                ->one();
            if ($data) {
                return $data['name_ru'];
            }
        }
        return null;
    }

    // получает название региона
    public function getRegion($id_geo_region = null)
    {
        if ($id_geo_region) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_region')
                ->where([
                    'id_geo_region' => $id_geo_region,
                ])
                ->one();
            if ($data) {
                return $data['name_ru'];
            }
        }
        return null;
    }

    // получает название страны
    public function getCountry($id_geo_country = null)
    {
        if ($id_geo_country) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_country')
                ->where([
                    'id_geo_country' => $id_geo_country,
                ])
                ->one();
            if ($data) {
                return $data['name_ru'];
            }
        }
        return null;
    }
}