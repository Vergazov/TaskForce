<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property int|null $price
 * @property string|null $comment
 * @property int $task_id
 * @property int $user_id
 * @property string|null $dt_add
 * @property boolean|null is_accepted
 * @property boolean|null is_denied
 *
 * @property Task $task
 * @property User $user
 */
class Response extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'response';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'task_id', 'user_id'], 'integer'],
            [['task_id', 'user_id'], 'required'],
            [['dt_add'], 'safe'],
            [['is_accepted'], 'boolean'],
            [['is_denied'], 'boolean'],
            [['comment'], 'string', 'max' => 255],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'comment' => 'Comment',
            'task_id' => 'Task ID',
            'is_accepted' => 'Is_accepted',
            'is_denied' => 'Is denied',
            'dt_add' => 'Dt Add',
        ];
    }


    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getIsDeny()
    {
        $denyStatus = $this;
    }
}
