<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $id ID
 * @property string $name Наименование
 * @property string $alias Алиас
 * @property string $title Заголовок
 * @property string $meta_keywords Мета ключи
 * @property string $meta_description Мета описание
 * @property string $annotation Аннотация
 * @property string $content Содержание
 * @property int $status Статус
 * @property int $is_folder Папка?
 * @property int $parent_id Родитель
 * @property int $item_id Элемент
 * @property int $template_id Шаблон
 * @property int $created_at Время создания
 * @property int $updated_at Время изменения
 * @property int $created_by Создал
 * @property int $updated_by Изменил
 * @property int $position Позиция (перед)
 * @property int $access Доступ
 * @property string $ip IP пользователя
 * @property string $user_agent Данные браузера
 *
 * @property Comment[] $comments
 * @property Document $item
 * @property Document[] $documents
 * @property Document $parent
 * @property Document[] $documents0
 * @property Template $template
 * @property User $createdBy
 * @property User $updatedBy
 * @property Like[] $likes
 * @property User[] $users
 * @property ValueFile[] $valueFiles
 * @property ValueInt[] $valueInts
 * @property ValueNumeric[] $valueNumerics
 * @property ValuePrice[] $valuePrices
 * @property ValuePrice[] $valuePrices0
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
            [['name', 'alias'], 'required'],
            [['meta_keywords', 'meta_description', 'annotation', 'content'], 'string'],
            [['status', 'is_folder', 'parent_id', 'item_id', 'template_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'position', 'access'], 'integer'],
            [['name', 'alias', 'title', 'user_agent'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 20],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['item_id' => 'id']],
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
            'title' => Yii::t('app', 'Заголовок'),
            'meta_keywords' => Yii::t('app', 'Мета ключи'),
            'meta_description' => Yii::t('app', 'Мета описание'),
            'annotation' => Yii::t('app', 'Аннотация'),
            'content' => Yii::t('app', 'Содержание'),
            'status' => Yii::t('app', 'Статус'),
            'is_folder' => Yii::t('app', 'Папка?'),
            'parent_id' => Yii::t('app', 'Родитель'),
            'item_id' => Yii::t('app', 'Элемент'),
            'template_id' => Yii::t('app', 'Шаблон'),
            'created_at' => Yii::t('app', 'Время создания'),
            'updated_at' => Yii::t('app', 'Время изменения'),
            'created_by' => Yii::t('app', 'Создал'),
            'updated_by' => Yii::t('app', 'Изменил'),
            'position' => Yii::t('app', 'Позиция (перед)'),
            'access' => Yii::t('app', 'Доступ'),
            'ip' => Yii::t('app', 'IP пользователя'),
            'user_agent' => Yii::t('app', 'Данные браузера'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Document::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['item_id' => 'id']);
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
    public function getDocuments0()
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
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['document_id' => 'id']);
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
    public function getValuePrices()
    {
        return $this->hasMany(ValuePrice::className(), ['discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValuePrices0()
    {
        return $this->hasMany(ValuePrice::className(), ['document_id' => 'id']);
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
