<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.10.2018
 * Time: 6:31
 */

namespace common\widgets\TemplateOfElement\components;

use common\models\forms\DocumentForm;
use common\models\forms\ValueFileForm;
use common\models\forms\ValuePriceForm;
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
use yii\helpers\ArrayHelper;
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

    // записывает поле с ценой
    public function setPrice($field, $forms_field, $document_id, $value_currency, $discount_id, $item, $item_max, $item_store, $item_measure)
    {
        $modelValuePriceForm = ValuePriceForm::findOne([
            'field_id' => $field['id'],
            'document_id' => $document_id,
        ]);
        if (!$modelValuePriceForm) {
            $modelValuePriceForm = new ValuePriceForm();
            $modelValuePriceForm->type = $field['type'];
            $modelValuePriceForm->document_id = $document_id;
            $modelValuePriceForm->field_id = $field['id'];
        }

        $modelValuePriceForm->title = $field['name'];
        $modelValuePriceForm->price = (int) $forms_field[0];
        $modelValuePriceForm->currency = $value_currency;
        $modelValuePriceForm->item = $item;
        $modelValuePriceForm->item_max = $item_max;
        $modelValuePriceForm->item_store = $item_store;
        $modelValuePriceForm->item_measure = $item_measure;
        
        if ($discount_id) {
            $discountValue = (new \yii\db\Query())
                ->select(['value'])
                ->from('value_int')
                ->where([
                    'document_id' => $discount_id,
                    'type' => Constants::FIELD_TYPE_DISCOUNT,
                ])
                ->one();

            $modelValuePriceForm->discount_price = ($modelValuePriceForm->price / 100) * (100 - $discountValue['value']);
            $modelValuePriceForm->discount_id = $discount_id;
        } else {
            $modelValuePriceForm->discount_price = $modelValuePriceForm->price;
            $modelValuePriceForm->discount_id = null;
        }

        if (!$modelValuePriceForm->save()) {
            dd($modelValuePriceForm->errors);
        }
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
                $modelValueIntForm->value = strtotime($value);
                if (!$modelValueIntForm->save()) {
                    dd($modelValueIntForm->errors);
                }
            }
        } else {
            $i = 0;
            foreach ($manyValueIntForm as $modelValueIntForm) {
                /* @var $modelValueIntForm ValueIntForm */
                $modelValueIntForm->title = $field['name'];
                $modelValueIntForm->value = $forms_field[$i] ? strtotime($forms_field[$i]) : null;
                if (!$modelValueIntForm->save()) {
                    dd($modelValueIntForm->errors);
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


    /**
     * Записывает модель
     * */
    private function saveModel($fields, $model) {
        foreach ($fields as $item) {
            if ($item === false) {
                $item = null;
            }
            $model->value = $item;
            if (!$model->save()) {
                d($item);
                dd($model->errors);
            }
        }
    }

    /**
     * Получение значений
     * */
    public function getValue($field_id, $type, $document_id)
    {
        if ($type == Constants::FIELD_TYPE_INT ||
            $type == Constants::FIELD_TYPE_DISCOUNT ||
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
        } elseif ($type == Constants::FIELD_TYPE_PRICE) {
            return $this->getPriceData($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_FLOAT ||
            $type == Constants::FIELD_TYPE_NUM) {
            return $this->getNum($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_FLOAT_RANGE) {
            return $this->getNumRange($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_STRING ||
            $type == Constants::FIELD_TYPE_ADDRESS ||
            $type == Constants::FIELD_TYPE_EMAIL ||
            $type == Constants::FIELD_TYPE_URL ||
            $type == Constants::FIELD_TYPE_SOCIAL ||
            $type == Constants::FIELD_TYPE_YOUTUBE) {
            return $this->getStr($field_id, $document_id);
        } elseif ($type == Constants::FIELD_TYPE_DATE) {
            return $this->getDate($field_id, $document_id);
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

    /*
     * Возвращает значение по названию поля
     * */
    public function getValueByName($name, $templateData)
    {
        if ($templateData) {
            foreach ($templateData as $item) {
                if ($item['title'] == $name) {
                    return $item['value'];
                }
            }
        }

        return null;
    }

    /*
     * Возвращает поля шаблона пользователя значение по названию поля и ID пользователя
     * */
    public function getUserValueByName($name, $user_id)
    {
        $user = (new \yii\db\Query())
            ->select(['document_id'])
            ->from('user')
            ->where([
                'id' => $user_id
            ])
            ->one();

        if ($user && $user['document_id']) {
            $document = (new \yii\db\Query())
                ->select(['template_id'])
                ->from('document')
                ->where([
                    'id' => $user['document_id']
                ])
                ->one();
            $data = $this->getData($user['document_id'], $document['template_id']);

            $name = $this->getValueByName($name, $data);

            if ($name) {
                return $name;
            }
        }

        return null;
    }

    /**
     * Получает поля и значения документа с шаблоном
     *
     * @throws \yii\db\Exception
     */
    public function getData($document_id, $template_id)
    {
        $modelTemplateForm = TemplateForm::findOne($template_id);

        $result = [];

        if (isset($modelTemplateForm->fields)) {
            $i = 0;
            foreach ($modelTemplateForm->fields as $modelFieldForm) {
                /* @var $modelFieldForm FieldForm */
                if ($modelFieldForm->type == Constants::FIELD_TYPE_INT ||
                    $modelFieldForm->type == Constants::FIELD_TYPE_DISCOUNT) {
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
                    $dataCheckbox = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->all();
                    if ($dataCheckbox) {
                        $fieldData = (new \yii\db\Query())
                            ->select(['*'])
                            ->from('value_string')
                            ->where([
                                'field_id' => $dataCheckbox[0]['field_id']
                            ])
                            ->all();

                        $resultCheckbox = [];
                        foreach ($dataCheckbox as $checkbox) {
                            if (isset($fieldData[$checkbox['value']])) {
                                $resultCheckbox[] = $fieldData[$checkbox['value']];
                            }
                        }

                        $data = [];
                        $y = 0;
                        foreach ($resultCheckbox as $item) {
                            $data['title'] = $item['title'];
                            $data['value'][$y] = $item['value'];
                            $y++;
                        }
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_LIST_MULTY) {
                    $dataMulty = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->all();
                    if ($dataMulty) {
                        $fieldData = (new \yii\db\Query())
                            ->select(['*'])
                            ->from('value_string')
                            ->where([
                                'field_id' => $dataMulty[0]['field_id']
                            ])
                            ->all();
                        $listData = [];
                        foreach ($dataMulty as $item) {
                            $listData[] = $fieldData[$item['value']];
                        }
                        $data = [];
                        $y = 0;
                        foreach ($listData as $item) {
                            $data['title'] = $item['title'];
                            $data['value'][$y] = $item['value'];
                            $y++;
                        }
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_FLOAT) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_numeric')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                    if (!$data) {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_PRICE) {
                    $dataPrice = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_price')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();

                    if ($dataPrice) {
                        if ($dataPrice['discount_id']) {
                            $dataDiscount = (new \yii\db\Query())
                                ->select(['*'])
                                ->from('document')
                                ->where([
                                    'id' => $dataPrice['discount_id'],
                                ])
                                ->one();
                            if ($dataDiscount) {
                                $dataDiscountValues = $this->getData($dataDiscount['id'], $dataDiscount['template_id']);
                                if ($dataDiscountValues) {
                                    foreach ($dataDiscountValues as $dataDiscountValue) {
                                        if ($dataDiscountValue['type'] == Constants::FIELD_TYPE_DISCOUNT) {
                                            $dataDiscount['percent'] = $dataDiscountValue['value'];
                                        }
                                        if ($dataDiscountValue['type'] == Constants::FIELD_TYPE_DATE) {
                                            if (strtotime($dataDiscountValue['value']) < strtotime(Yii::$app->formatter->asDate(time()))) {
                                                Yii::$app->db->createCommand()
                                                    ->update('value_price', [
                                                        'discount_price' => $dataPrice['price'],
                                                        'discount_id' => null,
                                                    ], [
                                                        'id' => $dataPrice['id']
                                                    ])
                                                    ->execute();
                                                $dataPrice['discount_price'] = $dataPrice['price'];
                                            } else {
                                                $dataDiscount['date_end'] = $dataDiscountValue['value'];
                                            }
                                        }
                                    }
                                }
                                if (isset($dataDiscount['date_end'])) {
                                    $dataPrice = ArrayHelper::merge($dataPrice, $dataDiscount);
                                }
                            }

                        }
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = $dataPrice;
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
                    }
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
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_STRING ||
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
                    if (!$data) {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_DATE) {
                    $data = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('value_int')
                        ->where([
                            'field_id' => $modelFieldForm->id,
                            'document_id' => $document_id,
                        ])
                        ->one();
                    if ($data) {
                        $data['value'] = Yii::$app->formatter->asDate($data['value']);
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
                    }
                } elseif ($modelFieldForm->type == Constants::FIELD_TYPE_DATE_RANGE) {
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
                            $data['value'][$y] = Yii::$app->formatter->asDate($item['value']);
                            $y++;
                        }
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
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
                    if (!$data) {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
                    }
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
                        if (isset($data['value'])) {
                            $data['title'] = $modelFieldForm->name;
                            $data['value'] = $values[$data['value']]['value'];
                        }
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
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
                        $name = $this->getCityName($data['value']);
                        $data['value'] = $name;
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
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
                        $name = $this->getRegionName($data['value']);
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = $name;
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
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
                        $name = $this->getCountryName($data['value']);
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = $name;
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
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
                        $data['value'] = $data;
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
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
                    } else {
                        $data['title'] = $modelFieldForm->name;
                        $data['value'] = null;
                    }
                }

                if (isset($data) && isset($data['title'])) {
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

    /**
     * Получает значение целого
     */
    public function getInt($field_id, $document_id)
    {
        $value = (new \yii\db\Query())
            ->select(['value'])
            ->from('value_int')
            ->where([
                'field_id' => $field_id,
                'document_id' => $document_id,
            ])
            ->one();

        if ($value) {
            return $value['value'];
        }
        return null;
    }

    /**
     * Получает значение диапазона целых чисел и чекбоксов
     */
    public function getIntRange($field_id, $document_id)
    {
        $values = (new \yii\db\Query())
            ->select(['value'])
            ->from('value_int')
            ->where([
                'field_id' => $field_id,
                'document_id' => $document_id,
            ])
            ->all();

        if ($values) {
            $result = [];
            foreach ($values as $value) {
                /* @var $modelValueIntForm ValueIntForm */
                $result[] = $value['value'];
            }
            return $result;
        }
        return null;
    }

    /**
     * Получает информацию о цене
     */
    public function getPriceData($field_id, $document_id)
    {
        $priceData = (new \yii\db\Query())
            ->select(['*'])
            ->from('value_price')
            ->where([
                'field_id' => $field_id,
                'document_id' => $document_id,
            ])
            ->one();

        if ($priceData) {
            return $priceData;
        }
        return null;
    }

    /**
     * Получает значение дробного
     */
    public function getNum($field_id, $document_id)
    {
        $value = (new \yii\db\Query())
            ->select(['value'])
            ->from('value_numeric')
            ->where([
                'field_id' => $field_id,
                'document_id' => $document_id,
            ])
            ->one();

        if ($value) {
            return $value['value'];
        }
        return null;
    }

    /**
     * Получает значение диапазона целых чисел
     */
    public function getNumRange($field_id, $document_id)
    {
        $values = (new \yii\db\Query())
            ->select(['value'])
            ->from('value_numeric')
            ->where([
                'field_id' => $field_id,
                'document_id' => $document_id,
            ])
            ->all();

        if ($values) {
            $result = [];
            foreach ($values as $value) {
                /* @var $modelValueIntForm ValueIntForm */
                $result[] = $values['value'];
            }
            return $result;
        }
        return null;
    }

    /**
     * Получает значение строки
     */
    public function getStr($field_id, $document_id)
    {
        $value = (new \yii\db\Query())
            ->select(['value'])
            ->from('value_string')
            ->where([
                'field_id' => $field_id,
                'document_id' => $document_id,
            ])
            ->one();

        if ($value) {
            return $value['value'];
        }
        return null;
    }

    /**
     * Получает дату
     */
    public function getDate($field_id, $document_id)
    {
        $value = (new \yii\db\Query())
            ->select(['value'])
            ->from('value_int')
            ->where([
                'field_id' => $field_id,
                'document_id' => $document_id,
            ])
            ->one();

        if ($value) {
            return $value['value'] ? Yii::$app->formatter->asDate($value['value']) : null;
        }
        return null;
    }

    /**
     * Получает значение диапазона дат
     */
    public function getDateRange($field_id, $document_id)
    {
        $values = (new \yii\db\Query())
            ->select(['value'])
            ->from('value_int')
            ->where([
                'field_id' => $field_id,
                'document_id' => $document_id,
            ])
            ->all();

        if ($values) {
            $result = [];
            foreach ($values as $value) {
                /* @var $modelValueIntForm ValueIntForm */
                $result[] = $values['value'] ? Yii::$app->formatter->asDate($value['value']) : null;
            }
            return $result;
        }
        return null;
    }

    /**
     * Получает значение текста
     */
    public function getText($field_id, $document_id)
    {
        $value = (new \yii\db\Query())
            ->select(['value'])
            ->from('value_text')
            ->where([
                'field_id' => $field_id,
                'document_id' => $document_id,
            ])
            ->one();

        if ($value) {
            return $value['value'] ? Yii::$app->formatter->asDate($value['value']) : null;
        }
        return null;
    }

    /**
     * Получает файл
     */
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

    /**
     * Получает файлы
     */
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

    /**
     * Получает ID страны
     */
    public function getGeoId($name)
    {
        $session = Yii::$app->session;
        $id_geo_country = $session->get($name);
        $cookies = Yii::$app->request->cookies;
        if (!$id_geo_country && isset($cookies[$name])) {
            $id_geo_country = $cookies[$name]->value;
        }

        return $id_geo_country ? $id_geo_country : false;
    }

    /**
     * Получает название страны по ID
    */
    public function getCountryName($id_geo_country = null)
    {
        if (!$id_geo_country) {
            $session = Yii::$app->session;
            $id_geo_country = $session->get('id_geo_country');
            $cookies = Yii::$app->request->cookies;
            if (!$id_geo_country && isset($cookies['id_geo_country'])) {
                $id_geo_country = $cookies['id_geo_country']->value;
            }
        }

        if ($id_geo_country) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_country')
                ->where([
                    'id_geo_country' => $id_geo_country,
                ])
                ->one();
            if ($data) {
                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                    return $data['name_ru'];
                } else {
                    return $data['short_name'];
                }
            }
        }

        return null;
    }

    /**
     * Получает название страны по ID, для поиска
     */
    public function getCountrySearchName($id_geo_country = null)
    {
        if (!$id_geo_country) {
            $session = Yii::$app->session;
            $id_geo_country = $session->get('id_geo_country_search');
            $cookies = Yii::$app->request->cookies;
            if (!$id_geo_country && isset($cookies['id_geo_country_search'])) {
                $id_geo_country = $cookies['id_geo_country_search']->value;
            }
        }

        if ($id_geo_country) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_country')
                ->where([
                    'id_geo_country' => $id_geo_country,
                ])
                ->one();
            if ($data) {
                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                    return $data['name_ru'];
                } else {
                    return $data['short_name'];
                }
            }
        }

        return null;
    }

    // получает название региона
    public function getRegionName($id_geo_region = null)
    {
        if (!$id_geo_region) {
            $session = Yii::$app->session;
            $id_geo_region = $session->get('id_geo_region');
            $cookies = Yii::$app->request->cookies;
            if (!$id_geo_region && isset($cookies['id_geo_region'])) {
                $id_geo_region = $cookies['id_geo_region']->value;
            }
        }

        if ($id_geo_region) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_region')
                ->where([
                    'id_geo_region' => $id_geo_region,
                ])
                ->one();
            if ($data) {
                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                    return $data['name_ru'];
                } else {
                    return $data['name_en'];
                }
            }
        }
        return null;
    }

    // получает название региона
    public function getRegionSearchName($id_geo_region = null)
    {
        if (!$id_geo_region) {
            $session = Yii::$app->session;
            $id_geo_region = $session->get('id_geo_region_search');
            $cookies = Yii::$app->request->cookies;
            if (!$id_geo_region && isset($cookies['id_geo_region_search'])) {
                $id_geo_region = $cookies['id_geo_region_search']->value;
            }
        }

        if ($id_geo_region) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_region')
                ->where([
                    'id_geo_region' => $id_geo_region,
                ])
                ->one();
            if ($data) {
                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                    return $data['name_ru'];
                } else {
                    return $data['name_en'];
                }
            }
        }
        return null;
    }

    /**
     * Получает название города
     */
    public function getCityName($id_geo_city = null)
    {
        if (!$id_geo_city) {
            $session = Yii::$app->session;
            $id_geo_city = $session->get('id_geo_city');
            $cookies = Yii::$app->request->cookies;
            if (!$id_geo_city && isset($cookies['id_geo_city'])) {
                $id_geo_city = $cookies['id_geo_city']->value;
            }
        }

        if ($id_geo_city) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_city')
                ->where([
                    'id_geo_city' => $id_geo_city,
                ])
                ->one();
            if ($data) {
                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                    return $data['name_ru'];
                } else {
                    return $data['name_en'];
                }
            }
        }
        return null;
    }

    /**
     * Получает название города
     */
    public function getCitySearchName($id_geo_city = null)
    {
        if (!$id_geo_city) {
            $session = Yii::$app->session;
            $id_geo_city = $session->get('id_geo_city_search');
            $cookies = Yii::$app->request->cookies;
            if (!$id_geo_city && isset($cookies['id_geo_city_search'])) {
                $id_geo_city = $cookies['id_geo_city_search']->value;
            }
        }

        if ($id_geo_city) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_city')
                ->where([
                    'id_geo_city' => $id_geo_city,
                ])
                ->one();
            if ($data) {
                if (Yii::$app->language == 'ru' || Yii::$app->language == 'ru_RU') {
                    return $data['name_ru'];
                } else {
                    return $data['name_en'];
                }
            }
        }
        return null;
    }
}