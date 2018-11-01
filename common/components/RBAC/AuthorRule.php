<?php
namespace common\components\RBAC;

use Yii;
use yii\rbac\Rule;

/**
 * Проверяем authorID на соответствие залогиненному пользователю
 */
class AuthorRule extends Rule
{
    public $name;

    /** 
	 * @param string|int $user the user ID.
	 * @param Item $item the role or permission that this rule is associated with.
	 * @param array $params parameters passed to ManagerInterface::checkAccess().
	 * @return bool a value indicating whether the rule permits the role or permission it is associated with.
	 */
	 public function execute($user, $item, $params)
	 {
	     if (Yii::$app->user->can('admin') || Yii::$app->user->can('editor')) {
	         return true;
         }
		 return isset($params['model']) ? $params['model']->user_id == $user : false;
	 }
}