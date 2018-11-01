<?php

use yii\db\Migration;

class m000000_000003_I18n_init extends Migration
{
    public function up()
    {
        $this->createTable('{{%source_message}}', [
            'id'                    => $this->primaryKey()->comment('ID'),
            'category'              => $this->string()->comment(Yii::t('app', 'Категория сообщения')),
            'message'               => $this->text()->comment(Yii::t('app', 'Сообщение')),
            'location'              => $this->text()->comment(Yii::t('app', 'Местонахождение')),
            'hash'                  => $this->string(32)->defaultValue('')->comment(Yii::t('app', 'Хеш сообщения')),
        ]);

        $this->importData('{{%source_message}}',
            ['id', 'category', 'message', 'location', 'hash'],
            fopen(__DIR__ . '/../../console/migrations/csv/source_message.csv', "r"));

        $this->createTable('{{%message}}', [
            'id'                    => $this->integer()->notNull(),
            'language'              => $this->string(16)->notNull()->comment(Yii::t('app', 'Язык перевода')),
            'translation'           => $this->text()->comment(Yii::t('app', 'Перевод')),
            'hash'                  => $this->string(32)->notNull()->defaultValue('')->comment(Yii::t('app', 'Хеш перевода')),
        ]);

        $this->addPrimaryKey('pk_message_id_language', '{{%message}}', ['id', 'language']);
        $this->addForeignKey('fk_message_source_message', '{{%message}}', 'id', '{{%source_message}}', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('idx_source_message_category', '{{%source_message}}', 'category');
        $this->createIndex('idx_message_language', '{{%message}}', 'language');

        $this->importData('{{%message}}',
            ['id', 'language', 'translation', 'hash'],
            fopen(__DIR__ . '/../../console/migrations/csv/message.csv', "r"));
    }

    public function down()
    {
        $this->dropForeignKey('fk_message_source_message', '{{%message}}');
        $this->dropTable('{{%message}}');
        $this->dropTable('{{%source_message}}');
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
                    $item = str_replace("'", "\'", $item);
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
