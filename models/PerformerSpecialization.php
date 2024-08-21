<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "performer_specialization".
 *
 * @property int $id
 * @property int $specialization_id
 * @property int $performer_id
 *
 * @property User $performer
 * @property Specialization $specialization
 */
class PerformerSpecialization extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'performer_specialization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['specialization_id', 'performer_id'], 'required'],
            [['specialization_id', 'performer_id'], 'integer'],
            [['specialization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialization::class, 'targetAttribute' => ['specialization_id' => 'id']],
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
            'specialization_id' => 'Specialization ID',
            'performer_id' => 'Performer ID',
        ];
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
     * Gets query for [[Specialization]].
     *
     * @return ActiveQuery
     */
    public function getSpecialization(): ActiveQuery
    {
        return $this->hasOne(Specialization::class, ['id' => 'specialization_id']);
    }
}
