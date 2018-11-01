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

    public $file_extensions;

    public function rules()
    {
        $items = FieldExtend::rules();
        $items[] = [['name', 'input_date_from', 'input_date_to'], 'string', 'on' => ['create-field', 'update-field']];
        $items[] = [['list', 'file_extensions'], 'each', 'rule' => ['string'], 'on' => ['create-field', 'update-field']];

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
            $this->min = strtotime($this->input_date_from);
        }

        if ($this->input_date_to) {
            $this->max = strtotime($this->input_date_to);
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
            if ($this->min) {
                $this->input_date_from = Yii::$app->formatter->asDate($this->min);
            }
            if ($this->max) {
                $this->input_date_to = Yii::$app->formatter->asDate($this->max);
            }
        }
    }

    /**
     * @return boolean
     * @throws ErrorException
     */
    public function beforeDelete()
    {
        parent::beforeDelete();
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

        return true;
    }
}