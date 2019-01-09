<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 09.01.2019
 * Time: 7:12
 */

namespace common\widgets\TemplateOfElement\forms;

use common\models\forms\UserForm;
use Yii;
use common\models\Constants;
use common\models\forms\DocumentForm;
use yii\helpers\ArrayHelper;

/**
 *
 */
class ProfileTemplateForm extends DocumentForm
{
    public function rules()
    {
        $items = DocumentForm::rules();
        return $items;
    }

    public function attributeLabels()
    {
        $items = DocumentForm::attributeLabels();
        $items['parent_id'] = Yii::t('app', 'Выберите профиль');

        return $items;
    }

    public function beforeValidate()
    {
        parent::beforeValidate();
        $this->validateFields();

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // запись полей шаблона
        $this->saveFields();

        /* @var $modelUserForm UserForm */
        $modelUserForm = Yii::$app->user->identity;
        $modelUserForm->document_id = $this->id;
        $modelUserForm->save();
    }

    /**
     * Возвращает папки и документы находящиеся в корне
     * @return array
     */
    public function getSelectProfile($page)
    {
        // извлекаем возможные профили
        $manyProfiles = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'status' => Constants::STATUS_DOC_ACTIVE,
                'parent_id' => $page['id']
            ])
            ->orderBy(['position' => SORT_ASC])
            ->all();

        return ArrayHelper::map($manyProfiles, 'id', 'name');
    }
}