<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "value_price".
 *
 * @property int $id ID
 * @property string $title Название
 * @property string $price Цена
 * @property string $discount_price Цена со скидкой
 * @property string $currency Валюта
 * @property int $type Тип
 * @property int $document_id Документ
 * @property int $field_id Поле
 * @property int $discount_id Акция/cкидка
 * @property string $params Параметры
 *
 * @property Document $discount
 * @property Document $document
 * @property Field $field
 */
class ValuePrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'value_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'price', 'discount_price', 'currency', 'type', 'document_id', 'field_id'], 'required'],
            [['price', 'discount_price'], 'number'],
            [['type', 'document_id', 'field_id', 'discount_id'], 'integer'],
            [['title', 'params'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 3],
            [['discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['discount_id' => 'id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['field_id'], 'exist', 'skipOnError' => true, 'targetClass' => Field::className(), 'targetAttribute' => ['field_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Название'),
            'price' => Yii::t('app', 'Цена'),
            'discount_price' => Yii::t('app', 'Цена со скидкой'),
            'currency' => Yii::t('app', 'Валюта'),
            'type' => Yii::t('app', 'Тип'),
            'document_id' => Yii::t('app', 'Документ'),
            'field_id' => Yii::t('app', 'Поле'),
            'discount_id' => Yii::t('app', 'Акция/cкидка'),
            'params' => Yii::t('app', 'Параметры'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscount()
    {
        return $this->hasOne(Document::className(), ['id' => 'discount_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Field::className(), ['id' => 'field_id']);
    }
}
