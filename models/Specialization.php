<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "specialization".
 *
 * @property int $id
 * @property string $name
 *
 * @property PerformerSpecialization[] $performerSpecializations
 */
class Specialization extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'specialization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
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
        ];
    }

    /**
     * Gets query for [[PerformerSpecializations]].
     *
     * @return ActiveQuery
     */
    public function getPerformers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'performer_id'])->viaTable('performer_specialization', ['specialization_id' => 'id']);
    }
}
