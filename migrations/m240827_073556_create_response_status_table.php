<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%response_status}}`.
 */
class m240827_073556_create_response_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%response_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%response_status}}');
    }
}
