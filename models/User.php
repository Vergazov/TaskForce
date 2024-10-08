<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $city_id
 * @property bool $is_performer
 * @property string|null $birthdate
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
class User extends ActiveRecord implements IdentityInterface
{
    public $passwordRepeat;
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
            [['name', 'email', 'password', 'city_id', 'is_performer'], 'required'],
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password','message' => "Пароли не совпадают"],
            [['city_id', 'failed_tasks'], 'integer'],
            [['is_performer'], 'boolean'],
            [['birthdate','created_at'], 'date', 'format' => 'php:Y-m-d'],
            [['info'], 'string'],
            [['name', 'password', 'avatar'], 'string', 'max' => 255,],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['phone'], 'string', 'max' => 30],
            [['telegram'], 'string', 'max' => 50],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
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
            'is_performer' => 'Роль',
            'birthdate' => 'Дата рождения',
            'phone' => 'Телефон',
            'telegram' => 'Телеграм',
            'info' => 'Информация',
            'avatar' => 'Аватар',
            'failed_tasks' => 'Проваленные задачи',
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
        return $this->getTasks0()->where(['status_id' => 4])->count();
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

    public function getResponseByPerformer($taskId)
    {
        return $this->getResponses()
            ->where(['user_id' => $this->id])
            ->where(['task_id'=> $taskId])
            ->one();
    }

    public function getFeedback(): ActiveQuery
    {
        return $this->hasMany(Feedback::class, ['user_id' => 'id']);
    }

    public function getFeedbackCount($id): string
    {
        $count = $this->getFeedback()->where(['user_id' => $id])->count();
        return \Yii::t(
            'app',
            $count . ' {n, plural, one{отзыв} few{отзыва} many{отзывов} other{отзывов}}',
            ['n' => $count]
        );
    }

    private function increaseFailCount() :void
    {
        ++$this->failed_tasks;
        $this->save(false);
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(UserStatus::class, ['id' => 'status_id']);
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
