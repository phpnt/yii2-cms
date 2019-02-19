<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "template_view".
 *
 * @property int $id ID
 * @property int $type Тип представления
 * @property string $view Представление
 * @property int $template_id Шаблон
 *
 * @property Template $template
 */
class TemplateView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type', 'template_id'], 'integer'],
            [['view'], 'string'],
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
            'type' => Yii::t('app', 'Тип представления'),
            'view' => Yii::t('app', 'Представление'),
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
}
