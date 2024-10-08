<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property string|null $comment
 * @property int|null $rating
 * @property int $task_id
 * @property string|null $dt_add
 *
 * @property Task $task
 */
class Feedback extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['rating', 'task_id'], 'integer'],
            [['task_id'], 'required'],
            [['dt_add'], 'safe'],
            [['comment'], 'string', 'max' => 255],
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
            'comment' => 'Comment',
            'rating' => 'Оценка',
            'task_id' => 'Task ID',
            'dt_add' => 'Dt Add',
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
