<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%response}}`.
 */
class m240827_073557_create_feedback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%feedback}}', [
            'id' => $this->primaryKey(),
            'comment' => $this->string(),
            'rating' => $this->integer(),
            'task_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'dt_add' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk-feedback-task_id',
            'feedback',
            'task_id',
            'task',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-feedback-user_id',
            'feedback',
            'user_id',
            'user',
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
            'fk-feedback-task_id',
            'feedback'
        );

        $this->dropForeignKey(
            'fk-feedback-user_id',
            'feedback'
        );

        $this->dropTable('{{%feedback}}');
    }
}
