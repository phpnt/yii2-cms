<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.10.2018
 * Time: 6:31
 */

namespace common\widgets\Comment\components;

use common\models\forms\ValueFileForm;
use Yii;
use common\models\Constants;
use common\models\forms\FieldForm;
use common\models\forms\TemplateForm;
use common\models\forms\ValueIntForm;
use common\models\forms\ValueNumericForm;
use common\models\forms\ValueStringForm;
use common\models\forms\ValueTextForm;
use yii\base\ErrorException;
use yii\base\BaseObject;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;

class CommentManage extends BaseObject
{
    public function init()
    {
        parent::init();
    }

    // получает дочерние комментарии
    public function getChilds($parent_id) {
        $comments = (new \yii\db\Query())
            ->select(['*'])
            ->from('comment')
            ->where([
                'parent_id' => $parent_id,
            ])
            ->andWhere(['!=', 'status', Constants::STATUS_DOC_BLOCKED])
            ->all();

        return $comments;
    }
}