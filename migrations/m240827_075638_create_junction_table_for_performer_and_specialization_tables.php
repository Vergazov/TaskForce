<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%performer_specialization}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%performer}}`
 * - `{{%specialization}}`
 */
class m240827_075638_create_junction_table_for_performer_and_specialization_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%performer_specialization}}', [
            'performer_id' => $this->integer(),
            'specialization_id' => $this->integer(),
            'PRIMARY KEY(performer_id, specialization_id)',
        ]);

        // creates index for column `performer_id`
        $this->createIndex(
            '{{%idx-performer_specialization-performer_id}}',
            '{{%performer_specialization}}',
            'performer_id'
        );

        // add foreign key for table `{{%performer}}`
        $this->addForeignKey(
            '{{%fk-performer_specialization-performer_id}}',
            '{{%performer_specialization}}',
            'performer_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `specialization_id`
        $this->createIndex(
            '{{%idx-performer_specialization-specialization_id}}',
            '{{%performer_specialization}}',
            'specialization_id'
        );

        // add foreign key for table `{{%specialization}}`
        $this->addForeignKey(
            '{{%fk-performer_specialization-specialization_id}}',
            '{{%performer_specialization}}',
            'specialization_id',
            '{{%specialization}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%performer}}`
        $this->dropForeignKey(
            '{{%fk-performer_specialization-performer_id}}',
            '{{%performer_specialization}}'
        );

        // drops index for column `performer_id`
        $this->dropIndex(
            '{{%idx-performer_specialization-performer_id}}',
            '{{%performer_specialization}}'
        );

        // drops foreign key for table `{{%specialization}}`
        $this->dropForeignKey(
            '{{%fk-performer_specialization-specialization_id}}',
            '{{%performer_specialization}}'
        );

        // drops index for column `specialization_id`
        $this->dropIndex(
            '{{%idx-performer_specialization-specialization_id}}',
            '{{%performer_specialization}}'
        );

        $this->dropTable('{{%performer_specialization}}');
    }
}
