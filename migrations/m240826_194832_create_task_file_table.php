<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_file}}`.
 */
class m240826_194832_create_task_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_file}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'task_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-task_file-task_id',
            'task_file',
            'task_id',
            'task',
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
            'fk-task_file-task_id',
            'task_file'
        );

        $this->dropTable('{{%task_file}}');
    }
}
