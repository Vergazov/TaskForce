<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%response}}`.
 */
class m240827_073558_create_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%response}}', [
            'id' => $this->primaryKey(),
            'price' => $this->integer(),
            'comment' => $this->string(),
            'task_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'status_id' => $this->integer(),
            'dt_add' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk-response-task_id',
            'response',
            'task_id',
            'task',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-response-user_id',
            'response',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-response-status_id',
            'response',
            'status_id',
            'response_status',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-response-task_id',
            'response'
        );

        $this->dropForeignKey(
            'fk-response-user_id',
            'response'
        );

        $this->dropForeignKey(
            'fk-response-status_id',
            'response'
        );

        $this->dropTable('{{%response}}');
    }
}
