<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m240826_183015_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->notNull(),
            'description' => $this->text()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'city_id' => $this->integer()->notNull(),
            'coordinates' => $this->string(100),
            'budget' => $this->integer(),
            'deadline' => $this->date(),
            'author_id' => $this->integer()->notNull(),
            'performer_id' => $this->integer()->notNull(),
            'status' => $this->string(50)->notNull(),
        ]);

        $this->addForeignKey(
            'fk-task-category_id',
            'task',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-task-city_id',
            'task',
            'city_id',
            'city',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-task-author_id',
            'task',
            'author_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-task-performer_id',
            'task',
            'performer_id',
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
            'fk-task-category_id',
            'task'
        );

        $this->dropForeignKey(
            'fk-task-city_id',
            'task'
        );

        $this->dropForeignKey(
            'fk-task-author_id',
            'task'
        );

        $this->dropForeignKey(
            'fk-task-performer_id',
            'task'
        );

        $this->dropTable('{{%task}}');
    }
}
