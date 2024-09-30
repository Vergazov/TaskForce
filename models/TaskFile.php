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
    public $path;
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
            [['path'], 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg','maxFiles' => 10],
            [['name'], 'string', 'max' => 255],
            [['task_id'], 'integer'],
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
            'path' => 'Путь',
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

    public function upload($taskId): void
    {
        if($this->path && $this->validate()){
            foreach ($this->path as $file) {
                $taskFile = new TaskFile;
                $newName = uniqid('upload_', true) . '.' . $file->extension;
                $file->saveAs('uploads/' . $newName);
                $taskFile->name = $newName;
                $taskFile->task_id = $taskId;
                $taskFile->save(false);
            }
        }
    }
}
