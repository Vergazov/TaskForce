<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "taskfile".
 *
 * @property int $id
 * @property string $name
 * @property int $task_id
 *
 * @property Task $task
 */
class TaskFile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['task_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'task_id' => 'Задача',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
