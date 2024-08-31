<?php

namespace common\fixtures\templates;

use app\models\User;
use Faker\Generator;
use Yii;

/**
 * @var $faker Generator
 * @var $index integer
 */

return [
    'name' => $faker->name,
    'email' => $faker->email,
    'password' => Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
    'city_id' => $faker->numberBetween(1, User::find()->count()),
];
