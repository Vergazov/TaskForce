<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property string|null $performer_comment
 * @property int|null $price
 * @property int $task_id
 * @property string|null $creator_comment
 * @property int|null $rating
 *
 * @property Task $task
 */
class Response extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'response';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['performer_comment', 'creator_comment'], 'string'],
            [['price', 'task_id', 'rating'], 'integer'],
            [['task_id'], 'required'],
            [['user_id'], 'required'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
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
            'performer_comment' => 'Performer Comment',
            'price' => 'Price',
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
            'creator_comment' => 'Creator Comment',
            'rating' => 'Rating',
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

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
