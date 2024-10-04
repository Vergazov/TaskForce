<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task_file".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $path
 * @property int|null $size
 * @property int|null $user_id
 * @property string|null $task_uid
 * @property string|null $dt_add
 *
 * @property User $user
 */
class TaskFile extends ActiveRecord
{
    public $file;
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
            [['file'], 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
            [['user_id', 'size'], 'integer'],
            [['dt_add'], 'safe'],
            [['name', 'path', 'task_uid'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'path' => 'Path',
            'size' => 'Size',
            'user_id' => 'User ID',
            'task_uid' => 'Task Uid',
            'dt_add' => 'Dt Add',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['task_uid' => 'task_uid']);
    }

    public function upload(): bool
    {
        $this->name = $this->file->name;
        $newName = uniqid('', true) . '.' . $this->file->getExtension();
        $this->path = '/uploads/' . $newName;
        $this->size = $this->file->size;
        if($this->save()){
            return $this->file->saveAs('@webroot/uploads/' . $newName);
        }

        return false;
    }
}
