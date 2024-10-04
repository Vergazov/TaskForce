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
            'name' => $this->string(),
            'path' => $this->string(),
            'size' => $this->integer(),
            'user_id' => $this->integer(),
            'task_uid' => $this->string(),
            'dt_add' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk-task_file-user_id',
            'task_file',
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
            'fk-task_file-user_id',
            'task_file'
        );

        $this->dropTable('{{%task_file}}');
    }
}
