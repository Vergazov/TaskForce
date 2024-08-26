<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city}}`.
 */
class m240826_164726_create_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'lat' => $this->string(50),
            'lng' => $this->string(50),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%city}}');
    }
}
