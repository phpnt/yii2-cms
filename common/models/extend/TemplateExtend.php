<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:27
 */

namespace common\models\extend;

use Yii;
use common\models\Constants;
use common\models\forms\DocumentForm;
use common\models\forms\FieldForm;
use common\models\Template;

/**
 * @property array $statusList
 * @property array $statusItem
 * @property array $statusI18nList
 * @property array $statusI18nItem
 *
 * @property DocumentForm[] $documents
 * @property FieldForm[] $fields
 */
class TemplateExtend extends Template
{
    /**
     * Возвращает массив статусов документа
     * @property $with_null boolean
     * @return array
     */
    public function getStatusList()
    {
        return [
            Constants::STATUS_DOC_WAIT =>  Yii::t('app', 'Не подтвержден'),
            Constants::STATUS_DOC_ACTIVE => Yii::t('app', 'Опубликован'),
            Constants::STATUS_DOC_BLOCKED => Yii::t('app', 'Заблокирован'),
        ];
    }

    /**
     * Возвращает статус документа
     */
    public function getStatusItem()
    {
        switch ($this->status) {
            case Constants::STATUS_DOC_WAIT:
                return '<span class="label label-warning">'.$this->getStatusList()[Constants::STATUS_DOC_WAIT].'</span>';
                break;
            case Constants::STATUS_DOC_ACTIVE:
                return '<span class="label label-success">'.$this->getStatusList()[Constants::STATUS_DOC_ACTIVE].'</span>';
                break;
            case Constants::STATUS_DOC_BLOCKED:
                return '<span class="label label-danger">'.$this->getStatusList()[Constants::STATUS_DOC_BLOCKED].'</span>';
                break;
        }
        return false;
    }

    /**
     * Возвращает массив статусов перевода
     * @property $with_null boolean
     * @return array
     */
    public function getStatusI18nList()
    {
        return [
            Constants::STATUS_I18N_ALL =>  Yii::t('app', 'Переводить все'),
            Constants::STATUS_I18N_NAMES => Yii::t('app', 'Поля и сообщения.'),
            Constants::STATUS_I18N_BLOCKED => Yii::t('app', 'Не переводить'),
        ];
    }

    /**
     * Возвращает статус перевода
     */
    public function getStatusI18nItem()
    {
        switch ($this->i18n) {
            case Constants::STATUS_I18N_ALL:
                return '<span class="label label-primary">'.$this->getStatusI18nList()[Constants::STATUS_I18N_ALL].'</span>';
                break;
            case Constants::STATUS_I18N_NAMES:
                return '<span class="label label-primary">'.$this->getStatusI18nList()[Constants::STATUS_I18N_NAMES].'</span>';
                break;
            case Constants::STATUS_I18N_BLOCKED:
                return '<span class="label label-primary">'.$this->getStatusI18nList()[Constants::STATUS_I18N_BLOCKED].'</span>';
                break;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(DocumentForm::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(FieldForm::className(), ['template_id' => 'id']);
    }
}