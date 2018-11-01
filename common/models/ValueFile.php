<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "value_file".
 *
 * @property int $id ID
 * @property string $title Название
 * @property string $name Имя файла
 * @property string $extension Расширение
 * @property int $size Размер
 * @property string $path Путь к файлу
 * @property int $type Тип
 * @property int $document_id Документ
 * @property int $field_id Поле
 * @property int $position Позиция
 *
 * @property Document $document
 * @property Field $field
 */
class ValueFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'value_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'name', 'extension', 'size', 'path', 'type', 'document_id', 'field_id'], 'required'],
            [['size', 'type', 'document_id', 'field_id', 'position'], 'integer'],
            [['title', 'name', 'extension', 'path'], 'string', 'max' => 255],
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
            'name' => Yii::t('app', 'Имя файла'),
            'extension' => Yii::t('app', 'Расширение'),
            'size' => Yii::t('app', 'Размер'),
            'path' => Yii::t('app', 'Путь к файлу'),
            'type' => Yii::t('app', 'Тип'),
            'document_id' => Yii::t('app', 'Документ'),
            'field_id' => Yii::t('app', 'Поле'),
            'position' => Yii::t('app', 'Позиция'),
        ];
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
