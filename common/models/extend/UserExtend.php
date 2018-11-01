<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:29
 */

namespace common\models\extend;

use common\models\Constants;
use common\models\forms\AuthAssignmentForm;
use common\models\forms\AuthItemForm;
use common\models\forms\GeoCityForm;
use common\models\forms\GeoCountryForm;
use common\models\forms\UserOauthKeyForm;
use common\models\User;
use Yii;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use yii\web\IdentityInterface;

/**
 * Класс пользователей
 * @property array $sexList
 * @property array $statusList
 * @property string $statusUser
 * @property array $userRoles
 * @property array $userRole
 *
 * @property AuthAssignmentForm[] $authAssignments
 * @property AuthItemForm[] $itemNames
 * @property UserOauthKeyForm[] $keys
 * @property GeoCityForm $geoCity
 * @property GeoCountryForm $geoCountry
 */

class UserExtend extends User implements IdentityInterface
{
    public function getSexList()
    {
        return [
            Constants::SEX_FEMALE => Yii::t('app', 'Женский'),
            Constants::SEX_MALE => Yii::t('app', 'Мужской'),
        ];
    }

    public function getUserRole()
    {
        $modelAuthAssignmentForm = AuthAssignmentForm::findOne(['user_id' => $this->id]);

        return $modelAuthAssignmentForm->itemName->description;
    }

    public function getUserRoles()
    {
        $data = (new \yii\db\Query())
            ->select(['*'])
            ->from('auth_item')
            ->where(['type' => Constants::TYPE_ROLE])
            ->all();

        return ArrayHelper::map($data, 'name', 'description');
    }

    public function getStatusList()
    {
        return [
            Constants::STATUS_WAIT => Yii::t('app', 'Не подтвержден'),
            Constants::STATUS_ACTIVE => Yii::t('app', 'Активен'),
            Constants::STATUS_BLOCKED => Yii::t('app', 'Заблокирован'),
        ];
    }

    /**
     * Возвращает статус пользователя
     */
    public function getStatusUser()
    {
        switch ($this->status) {
            case Constants::STATUS_WAIT:
                return '<span class="label label-warning">'.$this->statusList[Constants::STATUS_WAIT].'</span>';
                break;
            case Constants::STATUS_ACTIVE:
                return '<span class="label label-success">'.$this->statusList[Constants::STATUS_ACTIVE].'</span>';
                break;
            case Constants::STATUS_BLOCKED:
                return '<span class="label label-danger">'.$this->statusList[Constants::STATUS_BLOCKED].'</span>';
                break;
        }
        return false;
    }

     /**
     * Статусы пользователя
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            Constants::STATUS_BLOCKED => Yii::t('app', 'Заблокирован'),
            Constants::STATUS_ACTIVE => Yii::t('app', 'Активен'),
            Constants::STATUS_WAIT =>  Yii::t('app', 'Не активен'),
        ];
    }

    /**
     * Гендерный список
     * @return array
     */
    public static function getSexArray()
    {
        return [
            Constants::SEX_MALE =>  Yii::t('app', 'Мужской'),
            Constants::SEX_FEMALE => Yii::t('app', 'Женский'),
        ];
    }

    /**
     * Связи пользователь => роль
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignmentForm::className(), ['user_id' => 'id']);
    }

    /**
     * Роли и допуски (разрешения)
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItemForm::className(), ['name' => 'item_name'])->viaTable('lb_auth_assignment', ['user_id' => 'id']);
    }

    /**
     * Ключи авторизации соц. сетей и страницы соц. сетей
     * @return \yii\db\ActiveQuery
     */
    public function getKeys()
    {
        return $this->hasMany(UserOauthKeyForm::className(), ['user_id' => 'id']);
    }

    /**
     * Страна
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(GeoCountryForm::className(), ['id' => 'country_id']);
    }


    /**
     * Город
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GeoCityForm::className(), ['id' => 'city_id']);
    }

    /**
     * Поиск пользователя по Id
     * @param int|string $id - ID
     * @return null|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Поиск пользователя по Email
     * @param $email - электронная почта
     * @return null|static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Ключ авторизации
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * ID пользователя
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Проверка ключа авторизации
     * @param string $authKey - ключ авторизации
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Поиск по токену доступа (не поддерживается)
     * @param mixed $token - токен
     * @param null $type - тип
     * @throws NotSupportedException - Исключение "Не подерживается"
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(Yii::t('app', 'Поиск по токену не поддерживается.'));
    }

    /**
     * Проверка правильности пароля
     * @param $password - пароль
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Генераия Хеша пароля
     * @param $password - пароль
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Поиск по токену восстановления паролья
     * Работает и для неактивированных пользователей
     * @param $token - токен восстановления пароля
     * @return null|static
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * Генерация случайного авторизационного ключа
     * для пользователя
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Проверка токена восстановления пароля
     * согласно его давности, заданной константой EXPIRE
     * @param $token - токен восстановления пароля
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + Yii::$app->params['user.passwordResetTokenExpire'] >= time();
    }

    /**
     * Генерация случайного токена
     * восстановления пароля
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Очищение токена восстановления пароля
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Проверка токена подтверждения Email
     * @param $email_confirm_token - токен подтверждения электронной почты
     * @return null|static
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => Constants::STATUS_WAIT]);
    }

    /**
     * Генерация случайного токена
     * подтверждения электронной почты
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Очищение токена подтверждения почты
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * Сохраняем изображения после сохранения
     * данных пользователя
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->saveImage();
    }

    /**
     * Действия, выполняющиеся после авторизации.
     * Сохранение IP адреса и даты авторизации.
     *
     * Для активации текущего обновления необходимо
     * повесить текущую функцию на событие 'on afterLogin'
     * компонента user в конфигурационном файле.
     * @param $id - ID пользователя
     */
    public static function afterLogin($id)
    {
        self::getDb()->createCommand()->update(self::tableName(), [
            'ip' => $_SERVER["REMOTE_ADDR"],
            'login_at' => date('Y-m-d H:i:s')
        ], ['id' => $id])->execute();
    }

    /**
     * Сохранение изображения (аватара)
     * пользвоателя
     */
    public function saveImage()
    {
        if ($this->photo) {
            $this->removeImage();   // Сначала удаляем старое изображение
            $module = Yii::$app->controller->module;
            $path = $module->userPhotoPath; // Путь для сохранения аватаров
            $name = time() . '-' . $this->id; // Название файла
            $this->image = $path. '/' . $name . $this::EXT;   // Путь файла и название
            if (!file_exists($path)) {
                mkdir($path, 0777, true);   // Создаем директорию при отсутствии
            }
            if (is_object($this->photo)) {
                // Загружено через FileUploadInterface
                Image::thumbnail($this->photo->tempName, 200, 200)->save($this->image);   // Сохраняем изображение в формате 200x200 пикселей
            } else {
                // Загружено по ссылке с удаленного сервера
                file_put_contents($this->image, $this->photo);
            }
            $this::getDb()
                ->createCommand()
                ->update($this->tableName(), ['image' => $this->image], ['id' => $this->id])
                ->execute();
        }
    }

    /**
     * Удаляем изображение при его наличии
     */
    public function removeImage()
    {
        if ($this->image) {
            // Если файл существует
            if (file_exists($this->image)) {
                unlink($this->image);
            }
            // Не регистрация пользователя
            if (!$this->isNewRecord) {
                $this::getDb()
                    ->createCommand()
                    ->update($this::tableName(), ['image' => null], ['id' => $this->id])
                    ->execute();
            }
        }
    }

    /**
     * Список всех пользователей
     * @param bool $show_id - показывать ID пользователя
     * @return array - [id => Имя Фамилия (ID)]
     */
    public static function getAll($show_id = false)
    {
        $users = [];
        $model = self::find()->all();
        if ($model) {
            foreach ($model as $m) {
                $name = ($m->last_name) ? $m->first_name . " " . $m->last_name : $m->first_name;
                if ($show_id) {
                    $name .= " (".$m->id.")";
                }
                $users[$m->id] = $name;
            }
        }

        return $users;
    }
}