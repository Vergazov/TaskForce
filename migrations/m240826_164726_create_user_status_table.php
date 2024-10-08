<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status}}`.
 */
class m240826_164726_create_user_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_status}}');
    }
}
