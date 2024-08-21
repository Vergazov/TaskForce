<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $category_id
 * @property int $city_id
 * @property string|null $coordinates
 * @property int|null $budget
 * @property string|null $deadline
 * @property int $author_id
 * @property int $performer_id
 * @property string $status
 *
 * @property User $author
 * @property Category $category
 * @property City $city
 * @property User $performer
 * @property Response[] $responses
 * @property Taskfile[] $taskfiles
 */
class Task extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'description', 'category_id', 'city_id', 'author_id', 'performer_id', 'status'], 'required'],
            [['description'], 'string'],
            [['category_id', 'city_id', 'budget', 'author_id', 'performer_id'], 'integer'],
            [['deadline'], 'safe'],
            [['title', 'coordinates'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 50],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['performer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'city_id' => 'City ID',
            'coordinates' => 'Coordinates',
            'budget' => 'Budget',
            'deadline' => 'Deadline',
            'author_id' => 'Author ID',
            'performer_id' => 'Performer ID',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
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
     * Gets query for [[Performer]].
     *
     * @return ActiveQuery
     */
    public function getPerformer(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'performer_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Taskfiles]].
     *
     * @return ActiveQuery
     */
    public function getTaskfiles(): ActiveQuery
    {
        return $this->hasMany(TaskFile::class, ['task_id' => 'id']);
    }
}
