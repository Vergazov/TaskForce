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
            [['name', 'email', 'password', 'city_id', 'role'], 'required'],
            [['city_id', 'failed_tasks'], 'integer'],
            [['birthdate'], 'safe'],
            [['info'], 'string'],
            [['name', 'password', 'avatar'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 100],
            [['role', 'phone'], 'string', 'max' => 30],
            [['telegram'], 'string', 'max' => 50],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
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
            'email' => 'Email',
            'password' => 'Password',
            'city_id' => 'City ID',
            'role' => 'Role',
            'birthdate' => 'Birthdate',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'info' => 'Info',
            'avatar' => 'Avatar',
            'failed_tasks' => 'Failed Tasks',
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

    /**
     * Gets query for [[Tasks0]].
     *
     * @return ActiveQuery
     */
    public function getTasks0(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['performer_id' => 'id']);
    }
}
