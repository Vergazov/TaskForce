<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m240826_164727_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'email' => $this->string(),
            'password' => $this->string(),
            'city_id' => $this->integer(),
            'role' => $this->string(),
            'birthdate' => $this->date(),
            'phone' => $this->string(),
            'telegram' => $this->string(),
            'info' => $this->text(),
            'avatar' => $this->string(),
            'failed_tasks' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-user-city_id',
            'user',
            'city_id',
            'city',
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
            'fk-user-city_id',
            'user'
        );

        $this->dropTable('{{%user}}');
    }
}
