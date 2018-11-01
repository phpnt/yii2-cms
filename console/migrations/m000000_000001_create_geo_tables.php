<?php

use yii\db\Migration;

class m000000_000001_create_geo_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%geo_country}}', [
            'id_geo_country'    => $this->primaryKey()->comment(Yii::t('app', 'ID')),
            'continent'         => $this->string(2)->notNull()->comment(Yii::t('app', 'Континент')),
            'name_ru'           => $this->string(128)->notNull()->comment(Yii::t('app', 'Русское название')),
            'lat'               => $this->decimal(6,2)->notNull()->comment(Yii::t('app', 'Широта')),
            'lon'               => $this->decimal(6,2)->notNull()->comment(Yii::t('app', 'Долгота')),
            'timezone'          => $this->string(30)->notNull()->comment(Yii::t('app', 'Временная зона')),
            'iso2'              => $this->string(2)->notNull()->comment(Yii::t('app', 'ISO2')),
            'short_name'        => $this->string(80)->notNull()->comment(Yii::t('app', 'Короткое название')),
            'long_name'         => $this->string(80)->notNull()->comment(Yii::t('app', 'Длинное название')),
            'iso3'              => $this->string(3)->notNull()->comment(Yii::t('app', 'ISO3')),
            'num_code'          => $this->string(6)->notNull()->comment(Yii::t('app', 'Цифровой код')),
            'un_member'         => $this->string(12)->notNull()->comment(Yii::t('app', 'Участник')),
            'calling_code'      => $this->string(8)->notNull()->comment(Yii::t('app', 'Телефонный код')),
            'cctld'             => $this->string(5)->notNull()->comment(Yii::t('app', 'Доменная зона')),
            'phone_number_digits' => $this->integer(2)->defaultValue(0)->comment(Yii::t('app', 'Количество цифр в телефонном номере')),
            'currency'          => $this->string(3)->notNull()->comment(Yii::t('app', 'Валюта')),
            'system_measure'    => $this->smallInteger(1)->comment(Yii::t('app', 'Система измерения')),
            'active'            => $this->boolean()->defaultValue(1)->comment(Yii::t('app', 'Активный')),
        ], $tableOptions);

        $this->importData('geo_country',
            ['id_geo_country', 'continent', 'name_ru', 'lat', 'lon', 'timezone', 'iso2', 'short_name', 'long_name', 'iso3', 'num_code', 'un_member', 'calling_code', 'cctld', 'phone_number_digits', 'currency', 'system_measure', 'active'],
            fopen(__DIR__ . '/../../console/migrations/csv/geo_country.csv', "r"));

        $this->createTable('{{%geo_region}}', [
            'id_geo_region'     => $this->primaryKey()->comment(Yii::t('app', 'ID')),
            'iso'               => $this->string(7)->comment(Yii::t('app', 'ISO')),
            'name_ru'           => $this->string(128)->notNull()->comment(Yii::t('app', 'Русское название')),
            'name_en'           => $this->string(128)->notNull()->comment(Yii::t('app', 'Английское название')),
            'timezone'          => $this->string(30)->notNull()->comment(Yii::t('app', 'Временная зона')),
            'okato'             => $this->string(4)->comment(Yii::t('app', 'ОКАТО')),
            'id_geo_country'    => $this->integer()->notNull()->comment(Yii::t('app', 'Страна'))
        ], $tableOptions);

        $this->createIndex('id_geo_country_index', '{{%geo_region}}', 'id_geo_country');
        $this->addForeignKey('geo_region_geo_country_fk', '{{%geo_region}}', 'id_geo_country', '{{%geo_country}}', 'id_geo_country', 'CASCADE', 'CASCADE');

        $this->importData('geo_region',
            ['id_geo_region', 'iso', 'name_ru', 'name_en', 'timezone', 'okato', 'id_geo_country'],
            fopen(__DIR__ . '/../../console/migrations/csv/geo_region.csv', "r"));

        $this->createTable('{{%geo_city}}', [
            'id_geo_city'   => $this->primaryKey()->comment(Yii::t('app', 'ID')),
            'name_ru'       => $this->string(128)->notNull()->comment(Yii::t('app', 'Русское название')),
            'name_en'       => $this->string(128)->notNull()->comment(Yii::t('app', 'Английское название')),
            'lat'           => $this->decimal(6,2)->notNull()->comment(Yii::t('app', 'Широта')),
            'lon'           => $this->decimal(6,2)->notNull()->comment(Yii::t('app', 'Долгота')),
            'okato'         => $this->string(20)->comment(Yii::t('app', 'ОКАТО')),
            'id_geo_region' => $this->integer()->notNull()->comment(Yii::t('app', 'Регион'))
        ], $tableOptions);

        $this->createIndex('id_geo_region_index', '{{%geo_city}}', 'id_geo_region');
        $this->addForeignKey('geo_city_geo_region_fk', '{{%geo_city}}', 'id_geo_region', '{{%geo_region}}', 'id_geo_region', 'CASCADE', 'CASCADE');

        $this->importData('geo_city',
            ['id_geo_city', 'name_ru', 'name_en', 'lat', 'lon', 'okato', 'id_geo_region'],
            fopen(__DIR__ . '/../../console/migrations/csv/geo_city.csv', "r"));
    }

    public function safeDown()
    {
        $this->dropTable('{{%geo_city}}');
        $this->dropTable('{{%geo_region}}');
        $this->dropTable('{{%geo_country}}');
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
