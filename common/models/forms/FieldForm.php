<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:25
 */

namespace common\models\forms;

use Yii;
use common\models\Constants;
use common\models\extend\FieldExtend;
use yii\base\ErrorException;
use yii\db\StaleObjectException;
use yii\helpers\Json;

class FieldForm extends FieldExtend
{
    public $list = [];
    public $item;

    public $input_date_from;
    public $input_date_to;

    public $file;
    public $few_files;
    public $file_extensions;

    public function rules()
    {
        $items = FieldExtend::rules();
        $items[] = [['name', 'input_date_from', 'input_date_to'], 'string', 'on' => ['create-field', 'update-field']];
        $items[] = [['list', 'file_extensions'], 'each', 'rule' => ['string'], 'on' => ['create-field', 'update-field']];
        $items[] = [['file'], 'file', 'skipOnEmpty' => true, 'on' => ['create-element', 'update-element']];
        $items[] = [['few_files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 20, 'on' => ['create-element', 'update-element']];

        return $items;
    }

    public function attributeLabels()
    {
        $items = FieldExtend::attributeLabels();
        $items['input_date_from'] = Yii::t('app', 'Минимальная дата');
        $items['input_date_to'] = Yii::t('app', 'Максимальная дата');
        $items['file_extensions'] = Yii::t('app', 'Расширение файлов');

        return $items;
    }

    public function beforeValidate()
    {
        parent::beforeValidate();
        if ($this->type == Constants::FIELD_TYPE_LIST && $this->list[0] == '') {
            $this->list = null;
            $this->addError('item', Yii::t('app', 'Список не может быть пустым.'));
        }

        if ($this->input_date_from) {
            $this->min_val = strtotime($this->input_date_from);
        }

        if ($this->input_date_to) {
            $this->max_val = strtotime($this->input_date_to);
        }
        return true;
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($this->file_extensions) {
            $file_extensions = [];
            foreach ($this->file_extensions as $extension) {
                $file_extensions['file_extensions'][] = $this->getFileExtItem($extension);
            }

            $this->params = Json::encode($file_extensions);
        }

        /* Если позиция не указана, ставим номер по умолчанию */
        if (!$this->position && $this->template_id) {
            dd($this->attributes);
            $this->position = (new \yii\db\Query())
                ->select(['*'])
                ->from('field')
                ->where([
                    'template_id' => $this->template_id,
                ])
                ->count();
        } elseif ($this->position && $this->oldAttributes['position'] != $this->position && $this->template_id) {
            dd($this->attributes);
            /* Ранее предшествующий элемент */
            $beforeItem = (new \yii\db\Query())
                ->select(['*'])
                ->from('field')
                ->where([
                    'position' => $this->oldAttributes['position'] - 1,
                    'template_id' => $this->template_id
                ])
                ->one();

            // если позиция изменена
            if ($beforeItem['id'] != $this->position) {
                /* Будущий предшествующий элемент */
                $beforeItem = (new \yii\db\Query())
                    ->select(['*'])
                    ->from('field')
                    ->where([
                        'id' => $this->position,
                        'template_id' => $this->template_id
                    ])
                    ->one();

                $items = (new \yii\db\Query())
                    ->select(['id', 'position'])
                    ->from('field')
                    ->where([
                        'template_id' => $this->template_id,
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
                        $db->createCommand()->update('field', ['position' => $i], 'id='.$item['id'])->execute();
                        $i++;
                        $this->position = $i;
                    } else {
                        $db->createCommand()->update('field', ['position' => $i], 'id='.$item['id'])->execute();
                    }
                    $i++;
                }
            } else {
                $this->position = $beforeItem['position'] + 1;
            }
        }

        return true;
    }

    /**
     * @throws ErrorException
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->list) {
            if (isset($this->valueStrings)) {
                foreach ($this->valueStrings as $modelValueStringForm) {
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
            foreach ($this->list as $item) {
                $modelValueStringForm = new ValueStringForm();
                $modelValueStringForm->title = $this->name;
                $modelValueStringForm->value = $item;
                $modelValueStringForm->type = $this->type;
                $modelValueStringForm->field_id = $this->id;
                $modelValueStringForm->save();
            }
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        if (isset($this->valueStrings)) {
            foreach ($this->valueStrings as $modelValueStringForm) {
                $this->list[] = $modelValueStringForm->value;
            }
        }
        if ($this->type == Constants::FIELD_TYPE_DATE || $this->type == Constants::FIELD_TYPE_DATE_RANGE) {
            if ($this->min_val) {
                $this->input_date_from = Yii::$app->formatter->asDate($this->min_val);
            }
            if ($this->max_val) {
                $this->input_date_to = Yii::$app->formatter->asDate($this->max_val);
            }
        }
        if (Yii::$app->controller->id != 'csv-manager' && $this->position) {
            /* Если позиция имеет какое-либо значение, определяем ID предыдущего элемента */
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('field')
                ->where([
                    'template_id' => $this->template_id,
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

        if (isset($this->valueInts)) {
            foreach ($this->valueInts as $modelValueIntForm) {
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

        if (isset($this->valueNumerics)) {
            foreach ($this->valueNumerics as $modelValueNumericForm) {
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

        if (isset($this->valueStrings)) {
            foreach ($this->valueStrings as $modelValueStringForm) {
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

        return true;
    }
}