<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property int $id ID
 * @property string $name Наименование
 * @property string $description Описание
 * @property string $mark Метка для шаблона
 * @property int $status Статус
 * @property int $add_rating Разрешена оценка элемента
 * @property int $add_comments Разрешены комментарии к элементу
 * @property int $use_filter Разрешить фильтр по полям шаблона
 * @property int $i18n Режим перевода
 *
 * @property Document[] $documents
 * @property Field[] $fields
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'mark'], 'required'],
            [['description'], 'string'],
            [['status', 'add_rating', 'add_comments', 'use_filter', 'i18n'], 'integer'],
            [['name', 'mark'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['mark'], 'unique'],
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
            'description' => Yii::t('app', 'Описание'),
            'mark' => Yii::t('app', 'Метка для шаблона'),
            'status' => Yii::t('app', 'Статус'),
            'add_rating' => Yii::t('app', 'Разрешена оценка элемента'),
            'add_comments' => Yii::t('app', 'Разрешены комментарии к элементу'),
            'use_filter' => Yii::t('app', 'Разрешить фильтр по полям шаблона'),
            'i18n' => Yii::t('app', 'Режим перевода'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::className(), ['template_id' => 'id']);
    }
}
