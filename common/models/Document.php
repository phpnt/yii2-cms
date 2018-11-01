<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $id ID
 * @property string $name Наименование
 * @property string $alias Алиас
 * @property string $route Маршрут
 * @property string $title Заголовок
 * @property string $meta_keywords Мета ключи
 * @property string $meta_description Мета описание
 * @property string $annotation Аннотация
 * @property string $content Содержание
 * @property int $status Статус
 * @property int $is_folder Папка?
 * @property int $parent_id Родитель
 * @property int $template_id Шаблон
 * @property int $created_at Время создания
 * @property int $updated_at Время изменения
 * @property int $created_by Создал
 * @property int $updated_by Изменил
 * @property int $position Позиция
 * @property int $access Доступ
 *
 * @property Basket[] $baskets
 * @property Document $parent
 * @property Document[] $documents
 * @property Template $template
 * @property User $createdBy
 * @property User $updatedBy
 * @property Like[] $likes
 * @property ValueFile[] $valueFiles
 * @property ValueInt[] $valueInts
 * @property ValueNumeric[] $valueNumerics
 * @property ValueString[] $valueStrings
 * @property ValueText[] $valueTexts
 * @property Visit[] $visits
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'alias', 'created_by', 'updated_by'], 'required'],
            [['meta_keywords', 'meta_description', 'annotation', 'content'], 'string'],
            [['status', 'is_folder', 'parent_id', 'template_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'position', 'access'], 'integer'],
            [['name', 'alias', 'route', 'title'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => Template::className(), 'targetAttribute' => ['template_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
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
            'alias' => Yii::t('app', 'Алиас'),
            'route' => Yii::t('app', 'Маршрут'),
            'title' => Yii::t('app', 'Заголовок'),
            'meta_keywords' => Yii::t('app', 'Мета ключи'),
            'meta_description' => Yii::t('app', 'Мета описание'),
            'annotation' => Yii::t('app', 'Аннотация'),
            'content' => Yii::t('app', 'Содержание'),
            'status' => Yii::t('app', 'Статус'),
            'is_folder' => Yii::t('app', 'Папка?'),
            'parent_id' => Yii::t('app', 'Родитель'),
            'template_id' => Yii::t('app', 'Шаблон'),
            'created_at' => Yii::t('app', 'Время создания'),
            'updated_at' => Yii::t('app', 'Время изменения'),
            'created_by' => Yii::t('app', 'Создал'),
            'updated_by' => Yii::t('app', 'Изменил'),
            'position' => Yii::t('app', 'Позиция'),
            'access' => Yii::t('app', 'Доступ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaskets()
    {
        return $this->hasMany(Basket::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Document::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['parent_id' => 'id']);
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Like::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueFiles()
    {
        return $this->hasMany(ValueFile::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueInts()
    {
        return $this->hasMany(ValueInt::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueNumerics()
    {
        return $this->hasMany(ValueNumeric::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueStrings()
    {
        return $this->hasMany(ValueString::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueTexts()
    {
        return $this->hasMany(ValueText::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(Visit::className(), ['document_id' => 'id']);
    }
}
