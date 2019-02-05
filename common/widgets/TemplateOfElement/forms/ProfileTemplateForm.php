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
    public $url;
    public $container;

    public function rules()
    {
        $items = DocumentForm::rules();
        $items[] = [['parent_id'], 'required'];
        $items[] = [['url', 'container'], 'string'];

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
        return true;
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        if ($this->validateFields()) {
            return true;
        }
        return false;
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
     * @return array | null
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

        if (count($manyProfiles) > 1) {
            return ArrayHelper::map($manyProfiles, 'id', 'name');
        }

        if (count($manyProfiles) == 1) {
            return $manyProfiles;
        }

        return null;
    }
}