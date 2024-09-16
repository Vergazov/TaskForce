<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $city_id
 * @property string $role
 * @property string|null $birthdate
 * @property string|null $dt_add
 * @property string|null $created_at
 * @property string|null $phone
 * @property string|null $telegram
 * @property string|null $info
 * @property string|null $avatar
 * @property int|null $failed_tasks
 *
 * @property City $city
 * @property PerformerSpecialization[] $performerSpecializations
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property Status $status
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'password', 'city_id', 'role_id'], 'required'],
            [['city_id', 'failed_tasks', 'role_id'], 'integer'],
            [['birthdate','dt_add','created_at'], 'date', 'format' => 'php:Y-m-d'],
            [['info'], 'string'],
            [['name', 'password', 'avatar'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 100],
            [['role', 'phone'], 'string', 'max' => 30],
            [['telegram'], 'string', 'max' => 50],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserStatus::class, 'targetAttribute' => ['status_id' => 'id']],
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
            'email' => 'Почта',
            'password' => 'Пароль',
            'city_id' => 'Город',
            'role_id' => 'Роль',
            'birthdate' => 'Дата рождения',
            'phone' => 'Телефон',
            'telegram' => 'Телеграм',
            'info' => 'Информация',
            'avatar' => 'Аватар',
            'failed_tasks' => 'Проваленные задачи',
            'dt_add' => 'Дата отклика',
            'status_id' => 'Статус пользователя',
            'created_at' => 'Дата регистрации',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return ActiveQuery
     */
    public function getRole(): ActiveQuery
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     * Gets query for [[PerformerSpecializations]].
     *
     * @return ActiveQuery
     */
    public function getSpecializations(): ActiveQuery
    {
        return $this->hasMany(Specialization::class, ['id' => 'specialization_id'])->viaTable('performer_specialization', ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['author_id' => 'id']);
    }

    public function getCompletedTasks($id)
    {
        return $this->getTasks0()->where(['performer_id' => $id, 'status_id' => 1])->count();
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return ActiveQuery
     */
    public function getTasks0(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['performer_id' => 'id']);
    }

    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['user_id' => 'id']);
    }

    public function getFeedback(): ActiveQuery
    {
        return $this->hasMany(Feedback::class, ['user_id' => 'id']);
    }

    public function getFeedbackCount($id)
    {
        return $this->getFeedback()->where(['user_id' => $id])->count();
    }

    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(UserStatus::class, ['id' => 'status_id']);
    }
}
