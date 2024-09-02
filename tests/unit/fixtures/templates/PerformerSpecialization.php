<?php

namespace unit\fixtures\templates;

use app\models\Specialization;
use app\models\User;
use Faker\Generator;

/**
 * @var $faker Generator
 */

return [
    'performer_id' => $faker->numberBetween(1, User::find()->count()),
    'specialization_id' => $faker->numberBetween(1, Specialization::find()->count()),
];
