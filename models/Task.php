<?php

namespace app\models;

use AvailableActions\AbstractAction;
use AvailableActions\AvailableActions;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $category_id
 * @property int|null $city_id
 * @property string|null $location
 * @property int|null $budget
 * @property string|null $expire_dt
 * @property int $author_id
 * @property int|null $performer_id
 * @property int $status_id
 * @property string|null $task_uid
 * @property string|null $dt_add
 *
 * @property User $author
 * @property Category $category
 * @property City $city
 * @property Feedback[] $feedbacks
 * @property User $performer
 * @property Response[] $responses
 * @property TaskStatus $status
 */
class Task extends ActiveRecord
{
    public $noResponses;
    public $noLocation;
    public $filterPeriod;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'category_id', 'author_id'], 'required'],
            [['status_id'], 'default', 'value' => 1],
            [['dt_add'], 'default', 'value' => Yii::$app->formatter->asDatetime('now','php:Y-m-d H:i:s')],
            [['description'], 'string'],
            [['category_id', 'city_id', 'budget', 'author_id', 'performer_id', 'status_id'], 'integer'],
            [['expire_dt', 'dt_add'], 'safe'],
            [['expire_dt'], 'date', 'format' => 'php:Y-m-d', 'min' => date('Y-m-d'), 'minString' => 'чем текущий день'],
            [['name'], 'string', 'max' => 50],
            [['location', 'task_uid'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['performer_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskStatus::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'city_id' => 'City ID',
            'location' => 'Location',
            'budget' => 'Budget',
            'expire_dt' => 'Expire Dt',
            'author_id' => 'Author ID',
            'performer_id' => 'Performer ID',
            'status_id' => 'Status ID',
            'task_uid' => 'Task Uid',
            'dt_add' => 'Dt Add',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public function getIsAuthor($currentUserId): bool
    {
        $author = $this->author;
        return $author->id === $currentUserId;
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Feedbacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Performer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(User::class, ['id' => 'performer_id']);
    }

    public function getIsPerformer($currentUserId): bool
    {
        $performer = $this->performer;
        return $performer->id === $currentUserId;
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(TaskStatus::class, ['id' => 'status_id']);
    }

    public function getTaskFiles()
    {
        return $this->hasMany(TaskFile::class, ['task_uid' => 'task_uid']);
    }

    public function getSearchQuery(): ActiveQuery
    {
        $query = self::find();

        $query->where(['status_id' => TaskStatus::STATUS_ACTIVE]);

        $query->andFilterWhere(['category_id' => $this->category_id]);

        if($this->noLocation) {
            $query->andWhere('location is NULL');
        }
        if($this->noResponses){
            $query->joinWith('responses r')->andWhere(['r.id' => null]);
        }
        if($this->filterPeriod){
            $query->andWhere('UNIX_TIMESTAMP(task.dt_add) > UNIX_TIMESTAMP() - :period',['period' => $this->filterPeriod]);
        }

        return $query->orderBy('dt_add DESC');
    }

    public function isInProgress(): bool
    {
        return $this->getStatus()->one()->id != TaskStatus::STATUS_IN_PROGRESS;
    }

    public function getResponsesQuery($user = null)
    {
        $allResponses = $this->getResponses();
        if($user && $user->getId() !== $this->author_id){
            $allResponses->where(['user_id' => $user->getId()]);
        }
        return $allResponses;
    }

    public function goToNextStatus(AbstractAction $action)
    {
        $actionManager = new AvailableActions($this->status->slug, $this->performer_id, $this->author_id);
        $nextStatusName = $actionManager->getNextStatus()[get_class($action)];

        $status = TaskStatus::findOne(['slug' => $nextStatusName]);
        $this->link('status', $status);
        $this->save();
    }
}
