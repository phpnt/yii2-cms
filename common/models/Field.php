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
 * @property int $is_unique Уникальное
 * @property int $min Минимальное значение
 * @property int $max Максимальное значение
 * @property string $params Дополнительные параметры
 * @property int $template_id Шаблон
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
            [['type', 'is_required', 'is_unique', 'min', 'max', 'template_id'], 'integer'],
            [['name', 'params'], 'string', 'max' => 255],
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
            'is_unique' => Yii::t('app', 'Уникальное'),
            'min' => Yii::t('app', 'Минимальное значение'),
            'max' => Yii::t('app', 'Максимальное значение'),
            'params' => Yii::t('app', 'Дополнительные параметры'),
            'template_id' => Yii::t('app', 'Шаблон'),
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
