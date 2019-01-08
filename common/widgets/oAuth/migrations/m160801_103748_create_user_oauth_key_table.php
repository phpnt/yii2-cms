<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_oauth_key`.
 */
class m160801_103748_create_user_oauth_key_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //Таблица авторизации пользователя user_oauth_key
        $this->createTable('{{%user_oauth_key}}', [
            'id'                => $this->primaryKey(),
            'user_id'           => $this->integer()->notNull(),
            'provider_id'       => $this->integer()->notNull(),
            'provider_user_id'  => $this->string()->notNull(),
            'page'              => $this->string(),
        ], $tableOptions);

        $this->addForeignKey('user_oauth_key_user_id_fk', '{{%user_oauth_key}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_oauth_key');
    }
}
