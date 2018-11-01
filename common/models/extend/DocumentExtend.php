<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:24
 */

namespace common\models\extend;

use common\models\forms\ValueFileForm;
use Yii;
use common\models\Constants;
use common\models\Document;
use common\models\forms\DocumentForm;
use common\models\forms\LikeForm;
use common\models\forms\TemplateForm;
use common\models\forms\UserForm;
use common\models\forms\ValueNumericForm;
use common\models\forms\ValueStringForm;
use common\models\forms\ValueTextForm;
use common\models\forms\VisitForm;
use yii\helpers\ArrayHelper;

/**
 * @property array $rootDocuments
 * @property string $folder
 * @property array $templatesList
 * @property array $parentsList
 * @property array $statusList
 * @property array $statusItem
 * @property string $allFolders
 * @property array $accessList
 * @property int $viewedDocument
 * @property int $likedDocument
 *
 * @property DocumentForm $parent
 * @property DocumentForm[] $childs
 * @property DocumentForm[] $documents
 * @property TemplateForm $template
 * @property UserForm $createdBy
 * @property UserForm $updatedBy
 * @property LikeForm[] $likes
 * @property ValueFileForm[] $valueFiles
 * @property ValueNumericForm[] $valueNumerics
 * @property ValueStringForm[] $valueStrings
 * @property ValueTextForm[] $valueTexts
 * @property VisitForm[] $visits
*/
class DocumentExtend extends Document
{
    /**
     * Возвращает количество лайков документа
     * @return int
     */
    public function getLikedDocument()
    {
        return (new \yii\db\Query())
            ->select(['*'])
            ->from('like')
            ->where([
                'document_id' => $this->id,
            ])
            ->count();
    }

    /**
     * Возвращает количество просмотров документа
     * @return int
     */
    public function getViewedDocument()
    {
        return (new \yii\db\Query())
            ->select(['*'])
            ->from('visit')
            ->where([
                'document_id' => $this->id,
            ])
            ->count();
    }

    /**
     * Возвращает упорядоченный массив всех папок
     * @return array
     */
    public function getAllFolders()
    {
        $manyDocumentForm = self::findAll(
            ['is_folder' => 1]
        );

        $data = [];

        foreach ($manyDocumentForm as $modelDocumentForm) {
            $data[$modelDocumentForm->id]['id'] = strval ($modelDocumentForm->id);
            $data[$modelDocumentForm->id]['parent'] = '#';
            $data[$modelDocumentForm->id]['text'] = Yii::t('app', $modelDocumentForm->name) . ' ' . $modelDocumentForm->statusItem;
            $data[$modelDocumentForm->id]['icon'] = 'fa fa-folder';
            $data[$modelDocumentForm->id]['state'] = [
                'opened' => true
            ];
        }

        foreach ($manyDocumentForm as $modelDocumentForm) {
            if ($modelDocumentForm->parent_id) {
                $data[$modelDocumentForm->id]['parent'] = strval ($modelDocumentForm->parent_id);
            }
        }

        $result = [];
        foreach ($data as $item) {
            $result[] = $item;
        }

        return $result;
    }

    /**
     * Возвращает массив доступов
     * @return array
     */
    public function getAccessList()
    {
        return [
            Constants::ACCESS_USER =>  Yii::t('app', 'Авторизованный пользователь'),
            Constants::ACCESS_ALL => Yii::t('app', 'Все пользователи'),
            Constants::ACCESS_GUEST => Yii::t('app', 'Гости'),
        ];
    }

    /**
     * Возвращает массив статусов документа
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
                return '<i class="fas fa-hourglass-half"></i>';
                break;
            case Constants::STATUS_DOC_ACTIVE:
                return '<i class="fas fa-check"></i>';
                break;
            case Constants::STATUS_DOC_BLOCKED:
                return '<i class="fas fa-ban"></i>';
                break;
        }
        return false;
    }

    /**
     * Возвращает папки и документы находящиеся в корне
     * @return array
     */
    public function getRootDocuments()
    {
        /* @var $parent self */
        $manyDocumentExtend = self::findAll([
            'parent_id' => null
        ]);

        return ArrayHelper::map($manyDocumentExtend, 'id', 'name');
    }

    /**
     * Возвращает папку, где находится элемент
     * @return string
     */
    public function getFolder()
    {
        /* @var $parent self */
        $parent = $this->parent;
        $string = '';

        while (isset($parent) && $parent) {
            $string .= $parent->name.'/';
            $parent = $parent->parent;
        }

        $folderArray = array_reverse (explode('/', $string));

        $string = '';
        foreach ($folderArray as $folder) {
            $string .= $folder.' / ';
        }

        return $string . Yii::t('app',
                $this->name . '<p><strong>({countFiles, plural, =0 {# документов} =1{# документ} one{# документ} few{# документа} many{# документов} other{# документа}})</strong></p>',
                ['countFiles' => count($this->childs)]);
    }

    /**
     * Возвращает список всех шаблонов
     * @return array
     */
    public function getTemplatesList()
    {
        $manyDocumentExtend = TemplateForm::find()
            ->all();

        return ArrayHelper::map($manyDocumentExtend, 'id', 'name');
    }

    /**
     * Возвращает список всех папок
     * @return array
     */
    public function getParentsList()
    {
        if ($this->id) {
            $manyDocumentExtend = self::find()
                ->where([
                    'is_folder' => 1
                ])
                ->andWhere(['!=', 'id', $this->id])
                ->all();
        } else {
            $manyDocumentExtend = self::find()
                ->where([
                    'is_folder' => 1
                ])
                ->all();
        }

        return ArrayHelper::map($manyDocumentExtend, 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(DocumentForm::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(DocumentForm::className(), ['parent_id' => 'id'])->where(['is_folder' => null]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(DocumentForm::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(TemplateForm::className(), ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserForm::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(UserForm::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(LikeForm::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueFiles()
    {
        return $this->hasMany(ValueFileForm::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueNumerics()
    {
        return $this->hasMany(ValueNumericForm::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueStrings()
    {
        return $this->hasMany(ValueStringForm::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueTexts()
    {
        return $this->hasMany(ValueTextForm::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(VisitForm::className(), ['document_id' => 'id']);
    }
}