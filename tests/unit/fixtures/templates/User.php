<?php

namespace unit\fixtures\templates;

use app\models\City;
use app\models\Role;
use app\models\UserStatus;
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
    'city_id' => $faker->numberBetween(1, City::find()->count()),
    'role_id' => $faker->numberBetween(1, Role::find()->count()),
    'birthdate' => $faker->date(),
    'phone' => $faker->phoneNumber,
    'telegram' => $faker->word,
    'info' => $faker->paragraph,
    'avatar' => $faker->imageUrl(),
    'failed_tasks' => $faker->numberBetween(0, 100),
    'status_id' => $faker->numberBetween(1, UserStatus::find()->count()),
    'created_at' => date('Y-m-d H:i:s', time() - random_int(259200, 8640000)),
];
