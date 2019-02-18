<?php
/**
 * @package   yii2-cms
 * @author    Yuri Shekhovtsov <shekhovtsovy@yandex.ru>
 * @copyright Copyright &copy; Yuri Shekhovtsov, lowbase.ru, 2015 - 2016
 * @version   1.0.0
 */

use common\models\Constants;
use yii\db\Migration;

class m000000_000002_cms_shop extends Migration
{
    // Администратор по умолчанию
    const ADMIN_EMAIL = 'admin@example.ru';
    const ADMIN_PASSWORD = 'admin';

    // Модератор по умолчанию
    const MODERATOR_EMAIL = 'editor@example.ru';
    const MODERATOR_PASSWORD = 'editor';

    // Тестировщик по умолчанию
    const TESTER_EMAIL = 'tester@example.ru';
    const TESTER_PASSWORD = 'tester';


    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        //Таблица пользователей user
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'email' => $this->string(100)->comment(Yii::t('app', 'Email')),
            'auth_key' => $this->string(32)->comment(Yii::t('app', 'Ключ авторизации')),
            'password_hash' => $this->string()->comment(Yii::t('app', 'Хеш пароля')),
            'password_reset_token' => $this->string()->comment(Yii::t('app', 'Токен восстановления пароля')),
            'email_confirm_token' => $this->string()->comment(Yii::t('app', 'Токен подтвердждения Email')),
            'status' => $this->smallInteger()->defaultValue(Constants::STATUS_WAIT)->comment(Yii::t('app', 'Статус')),
            'ip' => $this->string(20)->comment(Yii::t('app', 'IP')),
            'created_at' => $this->integer()->comment(Yii::t('app', 'Время создания')),
            'updated_at' => $this->integer()->comment(Yii::t('app', 'Время изменения')),
            'login_at' => $this->integer()->comment(Yii::t('app', 'Авторизован')),
            'document_id' => $this->integer()->comment(Yii::t('app', 'Профиль пользователя')),
        ], $tableOptions);

        //Индексы и ключи таблицы пользователей user
        $this->createIndex('user_email_index', '{{%user}}', 'email');
        $this->createIndex('user_status_index', '{{%user}}', 'status');

        $this->importData('user',
            ['id', 'email', 'auth_key', 'password_hash', 'password_reset_token', 'email_confirm_token', 'status', 'ip', 'created_at', 'updated_at', 'login_at', 'document_id'],
            fopen(__DIR__ . '/../../console/migrations/csv/user.csv', "r"));

        //Таблица авторизации пользователя user_oauth_key
        $this->createTable('{{%user_oauth_key}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'user_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Пользователь')),
            'provider_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Провайдер')),
            'provider_user_id' => $this->string()->notNull()->comment(Yii::t('app', 'Прользователь провайдера')),
            'page' => $this->string()->comment(Yii::t('app', 'Страница'))
        ], $tableOptions);

        //Индексы и ключи таблицы авторизации пользователя user_oauth_key
        $this->addForeignKey('user_oauth_key_user_id_fk', '{{%user_oauth_key}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->importData('user_oauth_key',
            ['id', 'user_id', 'provider_id', 'provider_user_id', 'page'],
            fopen(__DIR__ . '/../../console/migrations/csv/user_oauth_key.csv', "r"));

        /**
         * Миграции RBAC
         */

        //Таблица правил auth_rule
        $this->createTable('{{%auth_rule}}', [
            'name' => $this->string(64)->notNull()->comment(Yii::t('app', 'Название')),
            'data' => $this->text()->comment(Yii::t('app', 'Данные')),
            'created_at' => $this->integer()->comment(Yii::t('app', 'Время создания')),
            'updated_at' => $this->integer()->comment(Yii::t('app', 'Время изменения'))

        ], $tableOptions);

        //Индексы и ключи таблицы правил auth_rule
        $this->addPrimaryKey('auth_rule_pk', '{{%auth_rule}}', 'name');

        $this->importData('auth_rule',
            ['name', 'data', 'created_at', 'updated_at'],
            fopen(__DIR__ . '/../../console/migrations/csv/auth_rule.csv', "r"));

        //Таблица ролей и допусков auth_item
        $this->createTable('{{%auth_item}}', [
            'name' => $this->string(64)->notNull()->comment(Yii::t('app', 'Название')),
            'type' => $this->integer()->notNull()->comment(Yii::t('app', 'Тип')),
            'description' => $this->text()->notNull()->comment(Yii::t('app', 'Описание')),
            'rule_name' => $this->string(64)->comment(Yii::t('app', 'Правило')),
            'data' => $this->text()->comment(Yii::t('app', 'Данные')),
            'created_at' => $this->integer()->comment(Yii::t('app', 'Время создания')),
            'updated_at' => $this->integer()->comment(Yii::t('app', 'Время изменения'))
        ], $tableOptions);

        //Индексы и ключи таблицы ролей и допусков auth_item
        $this->addPrimaryKey('auth_item_name_pk', '{{%auth_item}}', 'name');
        $this->addForeignKey('auth_item_rule_name_fk', '{{%auth_item}}', 'rule_name', '{{%auth_rule}}',  'name', 'SET NULL', 'CASCADE');
        $this->createIndex('auth_item_type_index', '{{%auth_item}}', 'type');

        $this->importData('auth_item',
            ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'],
            fopen(__DIR__ . '/../../console/migrations/csv/auth_item.csv', "r"));

        //Таблица разрешений auth_item_child
        $this->createTable('{{%auth_item_child}}', [
            'parent' => $this->string(64)->notNull()->comment(Yii::t('app', 'Родитель')),
            'child' => $this->string(64)->notNull()->comment(Yii::t('app', 'Дочерний'))
        ], $tableOptions);

        //Индексы и ключи таблицы разрешений auth_item_child
        $this->addPrimaryKey('auth_item_child_pk', '{{%auth_item_child}}', array('parent', 'child'));
        $this->addForeignKey('auth_item_child_parent_fk', '{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('auth_item_child_child_fk', '{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');

        $this->importData('auth_item_child',
            ['parent', 'child'],
            fopen(__DIR__ . '/../../console/migrations/csv/auth_item_child.csv', "r"));

        //Таблица связи ролей auth_assignment
        $this->createTable('{{%auth_assignment}}', [
            'item_name' => $this->string(64)->notNull()->comment(Yii::t('app', '')),
            'user_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Пользователь')),
            'created_at' => $this->integer()->comment(Yii::t('app', 'Время создания')),
            'updated_at' => $this->integer()->comment(Yii::t('app', 'Время изменения'))
        ], $tableOptions);

        //Индексы и ключи таблицы связи ролей auth_assignment
        $this->addPrimaryKey('auth_assignment_pk', '{{%auth_assignment}}', array('item_name', 'user_id'));
        $this->addForeignKey('auth_assignment_item_name_fk', '{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('auth_assignment_user_id_fk', '{{%auth_assignment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->importData('auth_assignment',
            ['item_name', 'user_id', 'created_at', 'updated_at'],
            fopen(__DIR__ . '/../../console/migrations/csv/auth_assignment.csv', "r"));

        //Таблица шаблонов template
        $this->createTable('{{%template}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string()->notNull()->unique()->comment(Yii::t('app', 'Наименование')),
            'description' => $this->text()->notNull()->comment(Yii::t('app', 'Описание')),
            'mark' => $this->string()->notNull()->unique()->comment(Yii::t('app', 'Метка для шаблона')),
            'status' => $this->boolean()->notNull()->defaultValue(Constants::STATUS_WAIT)->comment(Yii::t('app', 'Статус')),
            'add_rating' => $this->boolean()->defaultValue(0)->comment(Yii::t('app', 'Разрешена оценка элемента')),
            'add_comments' => $this->boolean()->defaultValue(0)->comment(Yii::t('app', 'Разрешены комментарии к элементу')),
            'use_filter' => $this->boolean()->defaultValue(0)->comment(Yii::t('app', 'Разрешить фильтр по полям шаблона')),
            'i18n' => $this->boolean()->notNull()->defaultValue(Constants::STATUS_I18N_ALL)->comment(Yii::t('app', 'Режим перевода')), // 1 - перевод названия и значения,
                                                                                                                                                            // 2 - перевод названия,
                                                                                                                                                            // 6 - перевод отключен
        ] , $tableOptions);

        //Индексы и ключи таблицы шаблонов template
        $this->createIndex('template_name_index', '{{%template}}', 'name');

        $this->importData('template',
            ['id', 'name', 'description', 'mark', 'status', 'add_rating', 'add_comments', 'use_filter', 'i18n'],
            fopen(__DIR__ . '/../../console/migrations/csv/template.csv', "r"));

        //Таблица шаблонов template
        $this->createTable('{{%template_view}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'type' => $this->smallInteger(1)->notNull()->comment(Yii::t('app', 'Тип представления')),   // 0 - страница элемента, 1 - элемент в списке, 2 - элемент в корзине
            'view' => $this->text()->notNull()->comment(Yii::t('app', 'Представление')),
            'template_id' => $this->integer()->comment(Yii::t('app', 'Шаблон')),
        ] , $tableOptions);

        $this->addForeignKey('template_view_template_fk', '{{%template_view}}', 'template_id', '{{%template}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('template_view_type_index', '{{%template_view}}', 'type');
        $this->createIndex('template_view_template_id_index', '{{%template_view}}', 'template_id');

        $this->importData('template_view',
            ['id', 'type', 'view', 'template_id'],
            fopen(__DIR__ . '/../../console/migrations/csv/template_view.csv', "r"));

        //Таблица документов document
        $this->createTable('{{%document}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string()->notNull()->comment(Yii::t('app', 'Наименование')),
            'alias' => $this->string()->notNull()->comment(Yii::t('app', 'Алиас')),
            'title' => $this->string()->comment(Yii::t('app', 'Заголовок')),
            'meta_keywords' => $this->text()->comment(Yii::t('app', 'Мета ключи')),
            'meta_description' => $this->text()->comment(Yii::t('app', 'Мета описание')),
            'annotation' => $this->text()->comment(Yii::t('app', 'Аннотация')),
            'content' => $this->text()->comment(Yii::t('app', 'Содержание')),
            'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment(Yii::t('app', 'Статус')),
            'is_folder' => $this->boolean()->comment(Yii::t('app', 'Папка?')),
            'parent_id' => $this->integer()->comment(Yii::t('app', 'Родитель')),
            'template_id' => $this->integer()->comment(Yii::t('app', 'Шаблон')),
            'created_at' => $this->integer()->comment(Yii::t('app', 'Время создания')),
            'updated_at' => $this->integer()->comment(Yii::t('app', 'Время изменения')),
            'created_by' => $this->integer()->notNull()->comment(Yii::t('app', 'Создал')),
            'updated_by' => $this->integer()->notNull()->comment(Yii::t('app', 'Изменил')),
            'position' => $this->integer()->comment(Yii::t('app', 'Позиция (перед)')),
            'access' => $this->smallInteger(1)->defaultValue(Constants::ACCESS_USER)->comment(Yii::t('app', 'Доступ')),     // см в Constants
        ] , $tableOptions);

        //Индексы и ключи таблицы документов document
        $this->addForeignKey('user_document_id_fk', '{{%user}}', 'document_id', '{{%document}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('document_parent_id_fk', '{{%document}}', 'parent_id', '{{%document}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('document_template_id_fk', '{{%document}}', 'template_id', '{{%template}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('document_user_creator_pk', '{{%document}}', 'created_by', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('document_user_updater_fk', '{{%document}}', 'updated_by', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('document_name_index', '{{%document}}', 'name');
        $this->createIndex('document_alias_index', '{{%document}}', 'alias');
        $this->createIndex('document_status_index', '{{%document}}', 'status');

        $this->importData('document',
            ['id', 'name', 'alias', 'title', 'meta_keywords', 'meta_description', 'annotation', 'content', 'status', 'is_folder',
                'parent_id', 'template_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'position', 'access'],
            fopen(__DIR__ . '/../../console/migrations/csv/document.csv', "r"));

        //Дополнительные поля
        $this->createTable('{{%field}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string()->notNull()->comment(Yii::t('app', 'Наименование')),
            'type' => $this->integer()->notNull()->comment(Yii::t('app', 'Тип поля')),  // строка, целое число, массив и др.
            'is_required' => $this->boolean()->comment(Yii::t('app', 'Обязательное')),
            'error_required' => $this->string()->defaultValue(Yii::t('app', "Поле обязательно для заполнения."))->comment(Yii::t('app', 'Сообщение ошибки если поле не заполнено')),
            'is_unique' => $this->boolean()->comment(Yii::t('app', 'Уникальное')),
            'error_unique' => $this->string()->defaultValue(Yii::t('app', "Поле должно быть уникально."))->comment(Yii::t('app', 'Сообщение ошибки если поле уже есть в БД.')),
            'min_val' => $this->double()->defaultValue(0)->comment(Yii::t('app', 'Минимальное числовое значение {min_val}.')),
            'max_val' => $this->double()->defaultValue(0)->comment(Yii::t('app', 'Максимальное числовое значение {max_val}.')),
            'error_value' => $this->string()->defaultValue(Yii::t('app', "Поле должно быть числом от {min_val} до {max_val}."))->comment(Yii::t('app', 'Сообщение ошибки если поле не соответствует значениям')),
            'min_str' => $this->integer()->defaultValue(0)->comment(Yii::t('app', 'Минимальное количество символов {min_str}')),
            'max_str' => $this->integer()->defaultValue(0)->comment(Yii::t('app', 'Максимальное количество символов {max_str}')),
            'error_length' => $this->string()->defaultValue(Yii::t('app', "Поле должно содержать от {min_str} до {max_str} символов."))->comment(Yii::t('app', 'Сообщение ошибки если поле не соответствует кол-ву символов')),
            'params' => $this->string()->comment(Yii::t('app', 'Дополнительные параметры')),    // максимальное кол-во файлов, допустимые расширения
            'mask' => $this->string()->comment(Yii::t('app', 'Маска поля')),
            'hint' => $this->string()->comment(Yii::t('app', 'Подсказка для поля')),
            'template_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Шаблон')),
            'use_filter' => $this->boolean()->defaultValue(1)->comment(Yii::t('app', 'Использовать в фильтре')),
            'position' => $this->integer()->comment(Yii::t('app', 'Позиция (перед)')),
        ], $tableOptions);

        //Индексы и ключи таблицы полей field
        $this->addForeignKey('field_template_id_fk', '{{%field}}', 'template_id', '{{%template}}', 'id', 'CASCADE', 'CASCADE');

        $this->importData('field',
            ['id', 'name', 'type', 'is_required', 'error_required', 'is_unique', 'error_unique', 'min_val', 'max_val', 'error_value',
                'min_str', 'max_str', 'error_length', 'params', 'mask', 'hint', 'template_id', 'use_filter', 'position'],
            fopen(__DIR__ . '/../../console/migrations/csv/field.csv', "r"));

        // Значения целых цисел дополнительных полей
        $this->createTable('{{%value_int}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'title' => $this->string()->notNull()->comment(Yii::t('app', 'Название')),
            'value' => $this->integer()->comment(Yii::t('app', 'Значение')),
            'type' => $this->integer()->notNull()->comment(Yii::t('app', 'Тип')),
            'document_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Документ')),
            'field_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Поле')),
            'params' => $this->string()->comment(Yii::t('app', 'Параметры')),
        ], $tableOptions);

        //Индексы и ключи таблицы значений дат дополнительных полей
        $this->addForeignKey('value_int_document_id_fk', '{{%value_int}}', 'document_id', '{{%document}}', 'id', 'NO ACTION', 'CASCADE');
        $this->addForeignKey('value_int_field_id_fk', '{{%value_int}}', 'field_id', '{{%field}}', 'id', 'NO ACTION', 'CASCADE');
        $this->createIndex('value_int_name_index', '{{%value_int}}', 'value');

        $this->importData('value_int',
            ['id', 'title', 'value', 'type',  'document_id', 'field_id', 'params'],
            fopen(__DIR__ . '/../../console/migrations/csv/value_int.csv', "r"));

        //Числовые значения дополнительных полей
        $this->createTable('{{%value_numeric}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'title' => $this->string()->notNull()->comment(Yii::t('app', 'Название')),
            'value' => $this->double()->comment(Yii::t('app', 'Значение')),
            'type' => $this->integer()->notNull()->comment(Yii::t('app', 'Тип')),
            'document_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Документ')),
            'field_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Поле')),
            'params' => $this->string()->comment(Yii::t('app', 'Параметры')),
        ], $tableOptions);

        //Индексы и ключи таблицы числовых значений дополнительных полей
        $this->addForeignKey('value_numeric_document_id_fk', '{{%value_numeric}}', 'document_id', '{{%document}}', 'id', 'NO ACTION', 'CASCADE');
        $this->addForeignKey('value_numeric_field_id_fk', '{{%value_numeric}}', 'field_id', '{{%field}}', 'id', 'NO ACTION', 'CASCADE');
        $this->createIndex('value_numeric_name_index', '{{%value_numeric}}', 'value');

        $this->importData('value_numeric',
            ['id', 'title', 'value', 'type',  'document_id', 'field_id', 'params'],
            fopen(__DIR__ . '/../../console/migrations/csv/value_numeric.csv', "r"));

        //Строковые значения дополнительных полей
        $this->createTable('{{%value_string}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'title' => $this->string()->notNull()->comment(Yii::t('app', 'Название')),
            'value' => $this->string()->comment(Yii::t('app', 'Значение')),
            'type' => $this->integer()->notNull()->comment(Yii::t('app', 'Тип')),
            'document_id' => $this->integer()->comment(Yii::t('app', 'Документ')),
            'field_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Поле')),
            'params' => $this->string()->comment(Yii::t('app', 'Параметры')),
        ], $tableOptions);

        //Индексы и ключи таблицы строковых значений дополнительных полей
        $this->addForeignKey('value_string_document_id_fk', '{{%value_string}}', 'document_id', '{{%document}}', 'id', 'NO ACTION', 'CASCADE');
        $this->addForeignKey('value_string_field_id_fk', '{{%value_string}}', 'field_id', '{{%field}}', 'id', 'NO ACTION', 'CASCADE');
        $this->createIndex('value_string_name_index', '{{%value_string}}', 'value');

        $this->importData('value_string',
            ['id', 'title', 'value', 'type',  'document_id', 'field_id', 'params'],
            fopen(__DIR__ . '/../../console/migrations/csv/value_string.csv', "r"));

        //Текстовые значения дополнительных полей
        $this->createTable('{{%value_text}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'title' => $this->string()->notNull()->comment(Yii::t('app', 'Название')),
            'value' => $this->text()->comment(Yii::t('app', 'Значение')),
            'type' => $this->integer()->notNull()->comment(Yii::t('app', 'Тип')),
            'document_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Документ')),
            'field_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Поле')),
            'params' => $this->string()->comment(Yii::t('app', 'Параметры')),
        ], $tableOptions);

        //Индексы и ключи таблицы текстовых значений дополнительных полей
        $this->addForeignKey('value_text_document_id_fk', '{{%value_text}}', 'document_id', '{{%document}}', 'id', 'NO ACTION', 'CASCADE');
        $this->addForeignKey('value_text_field_id_fk', '{{%value_text}}', 'field_id', '{{%field}}', 'id', 'NO ACTION', 'CASCADE');

        $this->importData('value_text',
            ['id', 'title', 'value', 'type',  'document_id', 'field_id', 'params'],
            fopen(__DIR__ . '/../../console/migrations/csv/value_text.csv', "r"));

        $this->createTable('{{%value_file}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'title' => $this->string()->notNull()->comment(Yii::t('app', 'Название')),
            'name' => $this->string()->notNull()->comment(Yii::t('app', 'Имя файла')),
            'extension' => $this->string()->notNull()->comment(Yii::t('app', 'Расширение')),
            'size' => $this->integer()->comment(Yii::t('app', 'Размер')),
            'path' => $this->string()->notNull()->comment(Yii::t('app', 'Путь к файлу')),
            'type' => $this->integer()->comment(Yii::t('app', 'Тип')),
            'document_id' => $this->integer()->comment(Yii::t('app', 'Документ')),
            'field_id' => $this->integer()->comment(Yii::t('app', 'Поле')),
            'params' => $this->string()->comment(Yii::t('app', 'Параметры')),
        ], $tableOptions);

        //Индексы и ключи таблицы файлов
        $this->addForeignKey('value_file_document_id_fk', '{{%value_file}}', 'document_id', '{{%document}}', 'id', 'NO ACTION', 'CASCADE');
        $this->addForeignKey('value_file_field_id_fk', '{{%value_file}}', 'field_id', '{{%field}}', 'id', 'NO ACTION', 'CASCADE');

        $this->importData('value_file',
            ['id', 'title', 'name', 'extension', 'size', 'path', 'type',  'document_id', 'field_id', 'params'],
            fopen(__DIR__ . '/../../console/migrations/csv/value_file.csv', "r"));

        //Таблица просмотров документов visit
        $this->createTable('{{%visit}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'created_at' => $this->integer()->comment(Yii::t('app', 'Время создания')),
            'document_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Документ')),
            'ip' => $this->string(20)->notNull()->comment(Yii::t('app', 'IP')),
            'user_agent' => $this->text()->comment(Yii::t('app', 'Данные браузера')),
            'user_id' => $this->integer()->comment(Yii::t('app', 'Пользователь')),
        ], $tableOptions);

        //Индексы и ключи таблицы таблицы просмотров документов visit
        $this->addForeignKey('visit_document_id_fk', '{{%visit}}', 'document_id', '{{%document}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('visit_user_fk', '{{%visit}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->importData('visit',
            ['id', 'created_at', 'document_id', 'ip', 'user_agent', 'user_id'],
            fopen(__DIR__ . '/../../console/migrations/csv/visit.csv', "r"));

        //Таблица комментариев
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'text' => $this->text()->notNull()->comment('Комментарий'),
            'document_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Документ')),
            'ip' => $this->string(20)->notNull()->comment(Yii::t('app', 'IP')),
            'user_agent' => $this->text()->comment(Yii::t('app', 'Данные браузера')),
            'user_id' => $this->integer()->comment(Yii::t('app', 'Пользователь')),
            'parent_id' => $this->integer()->comment(Yii::t('app', 'Ответ на коммментарий')),
            'status' => $this->boolean()->defaultValue(Constants::STATUS_DOC_WAIT)->comment(Yii::t('app', 'Статус комментария')),
            'created_at' => $this->integer()->comment(Yii::t('app', 'Время создания')),
            'updated_at' => $this->integer()->comment(Yii::t('app', 'Время изменения')),
        ], $tableOptions);

        //Индексы и ключи таблицы комментариев
        $this->addForeignKey('comment_document_id_fk', '{{%comment}}', 'document_id', '{{%document}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('comment_user_fk', '{{%comment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('comment_comment_fk', '{{%comment}}', 'parent_id', '{{%comment}}', 'id', 'CASCADE', 'CASCADE');

        $this->importData('comment',
            ['id', 'text', 'document_id', 'ip', 'user_agent', 'user_id', 'parent_id', 'status', 'created_at', 'updated_at'],
            fopen(__DIR__ . '/../../console/migrations/csv/comment.csv', "r"));

        //Таблица просмотров документов visit
        $this->createTable('{{%like}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'like' => $this->boolean()->comment(Yii::t('app', 'Нравиться')),
            'dislike' => $this->boolean()->comment(Yii::t('app', 'Не нравиться')),
            'stars' => $this->smallInteger(3)->comment(Yii::t('app', 'Количество звезд')),
            'created_at' => $this->integer()->comment(Yii::t('app', 'Время создания')),
            'document_id' => $this->integer()->comment(Yii::t('app', 'Документ')),
            'comment_id' => $this->integer()->comment(Yii::t('app', 'Комментарий')),
            'ip' => $this->string(20)->notNull()->comment(Yii::t('app', 'IP')),
            'user_agent' => $this->text()->comment(Yii::t('app', 'Данные браузера')),
            'user_id' => $this->integer()->comment(Yii::t('app', 'Пользователь')),
        ], $tableOptions);

        //Индексы и ключи таблицы таблицы просмотров документов visit
        $this->addForeignKey('like_document_id_fk', '{{%like}}', 'document_id', '{{%document}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('like_comment_id_fk', '{{%like}}', 'comment_id', '{{%comment}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('like_user_fk', '{{%like}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->importData('like',
            ['id', 'like', 'dislike', 'stars', 'created_at', 'document_id', 'comment_id', 'ip', 'user_agent', 'user_id'],
            fopen(__DIR__ . '/../../console/migrations/csv/like.csv', "r"));

        //Таблица корзины
        $this->createTable('{{%basket}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'created_at' => $this->integer()->comment(Yii::t('app', 'Время создания')),
            'document_id' => $this->integer()->notNull()->comment(Yii::t('app', 'Документ')),
            'quantity' => $this->integer()->notNull()->comment(Yii::t('app', 'Количество')),
            'status' => $this->integer()->comment(Yii::t('app', 'Статус оплаты')),
            'ip' => $this->string(20)->notNull()->comment(Yii::t('app', 'IP')),
            'user_agent' => $this->text()->comment(Yii::t('app', 'Данные браузера')),
            'user_id' => $this->integer()->comment(Yii::t('app', 'Пользователь')),
        ], $tableOptions);

        //Индексы и ключи таблицы корзины
        $this->addForeignKey('basket_document_id_fk', '{{%basket}}', 'document_id', '{{%document}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('basket_user_fk', '{{%basket}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->importData('basket',
            ['id', 'created_at', 'document_id', 'quantity', 'status', 'ip', 'user_agent', 'user_id'],
            fopen(__DIR__ . '/../../console/migrations/csv/basket.csv', "r"));
    }

    public function down()
    {
        $this->dropForeignKey('document_user_creator_pk', '{{%document}}');
        $this->dropForeignKey('document_user_updater_fk', '{{%document}}');
        $this->dropForeignKey('like_document_id_fk', '{{%like}}');
        $this->dropForeignKey('like_user_fk', '{{%like}}');
        $this->dropForeignKey('visit_document_id_fk', '{{%visit}}');
        $this->dropForeignKey('visit_user_fk', '{{%visit}}');
        $this->dropForeignKey('basket_document_id_fk', '{{%basket}}');
        $this->dropForeignKey('basket_user_fk', '{{%basket}}');
        $this->dropTable('{{%auth_assignment}}');
        $this->dropTable('{{%auth_item_child}}');
        $this->dropTable('{{%auth_item}}');
        $this->dropTable('{{%auth_rule}}');
        $this->dropTable('{{%user_oauth_key}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%value_numeric}}');
        $this->dropTable('{{%value_string}}');
        $this->dropTable('{{%value_text}}');
        $this->dropTable('{{%value_int}}');
        $this->dropTable('{{%value_file}}');
        $this->dropTable('{{%field}}');
        $this->dropTable('{{%document}}');
        $this->dropTable('{{%template}}');
        $this->dropTable('{{%like}}');
        $this->dropTable('{{%visit}}');
        $this->dropTable('{{%basket}}');
    }

    /**
     * @throws ErrorException
     */
    private function importData($table_name, $attributes, $data)
    {
        $i = 0;
        $z = 0;
        $rows = [];
        while (($rowItems = fgetcsv($data, 1000, ";")) !== false) {
            if ($i > 0) {
                $y = 0;
                foreach ($rowItems as $item) {
                    $item = str_replace("'", "`", $item);
                    if ($item == '') {
                        $rows[$z][$y] = null;
                    } else {
                        $rows[$z][$y] = iconv('windows-1251', 'UTF-8', $item);
                    }
                    $y++;
                }
            }
            $i++;
            $z++;
        }
        $rowsArray = [];

        $i = 0;
        foreach ($rows as $row) {
            $rowsArray[$i] = $row;
            if ($i == 1000) {
                Yii::$app->db->createCommand()->batchInsert($table_name, $attributes, $rowsArray)->execute();
                unset($rowsArray);
                $i = 0;
            }
            $i++;
        }

        Yii::$app->db->createCommand()->batchInsert($table_name, $attributes, $rowsArray)->execute();
    }
}
