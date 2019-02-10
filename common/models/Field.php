<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "field".
 *
 * @property int $id ID
 * @property string $name Наименование
 * @property int $type Тип поля
 * @property int $is_required Обязательное
 * @property string $error_required Сообщение ошибки если поле не заполнено
 * @property int $is_unique Уникальное
 * @property string $error_unique Сообщение ошибки если поле уже есть в БД.
 * @property double $min_val Минимальное числовое значение {min_val}.
 * @property double $max_val Максимальное числовое значение {max_val}.
 * @property string $error_value Сообщение ошибки если поле не соответствует значениям
 * @property int $min_str Минимальное количество символов {min_str}
 * @property int $max_str Максимальное количество символов {max_str}
 * @property string $error_length Сообщение ошибки если поле не соответствует кол-ву символов
 * @property string $params Дополнительные параметры
 * @property string $mask Маска поля
 * @property string $hint Подсказка для поля
 * @property int $template_id Шаблон
 * @property int $use_filter Использовать в фильтре
 * @property int $position Позиция (после)
 *
 * @property Template $template
 * @property ValueFile[] $valueFiles
 * @property ValueInt[] $valueInts
 * @property ValueNumeric[] $valueNumerics
 * @property ValueString[] $valueStrings
 * @property ValueText[] $valueTexts
 */
class Field extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'template_id'], 'required'],
            [['type', 'is_required', 'is_unique', 'min_str', 'max_str', 'template_id', 'use_filter', 'position'], 'integer'],
            [['min_val', 'max_val'], 'number'],
            [['name', 'error_required', 'error_unique', 'error_value', 'error_length', 'params', 'mask', 'hint'], 'string', 'max' => 255],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => Template::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Наименование'),
            'type' => Yii::t('app', 'Тип поля'),
            'is_required' => Yii::t('app', 'Обязательное'),
            'error_required' => Yii::t('app', 'Сообщение ошибки если поле не заполнено'),
            'is_unique' => Yii::t('app', 'Уникальное'),
            'error_unique' => Yii::t('app', 'Сообщение ошибки если поле уже есть в БД.'),
            'min_val' => Yii::t('app', 'Минимальное числовое значение {min_val}.'),
            'max_val' => Yii::t('app', 'Максимальное числовое значение {max_val}.'),
            'error_value' => Yii::t('app', 'Сообщение ошибки если поле не соответствует значениям'),
            'min_str' => Yii::t('app', 'Минимальное количество символов {min_str}'),
            'max_str' => Yii::t('app', 'Максимальное количество символов {max_str}'),
            'error_length' => Yii::t('app', 'Сообщение ошибки если поле не соответствует кол-ву символов'),
            'params' => Yii::t('app', 'Дополнительные параметры'),
            'mask' => Yii::t('app', 'Маска поля'),
            'hint' => Yii::t('app', 'Подсказка для поля'),
            'template_id' => Yii::t('app', 'Шаблон'),
            'use_filter' => Yii::t('app', 'Использовать в фильтре'),
            'position' => Yii::t('app', 'Позиция (после)'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueFiles()
    {
        return $this->hasMany(ValueFile::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueInts()
    {
        return $this->hasMany(ValueInt::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueNumerics()
    {
        return $this->hasMany(ValueNumeric::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueStrings()
    {
        return $this->hasMany(ValueString::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueTexts()
    {
        return $this->hasMany(ValueText::className(), ['field_id' => 'id']);
    }
}
