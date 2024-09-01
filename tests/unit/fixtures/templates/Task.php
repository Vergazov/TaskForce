<?php

namespace common\fixtures\templates;

use app\models\Category;
use app\models\City;
use app\models\Status;
use app\models\User;
use Faker\Generator;

/**
 * @var $faker Generator
 */

return [
    'title' => $faker->paragraph(1),
    'description' => $faker->paragraph(1),
    'category_id' => $faker->numberBetween(1, Category::find()->count()),
    'city_id' => $faker->numberBetween(1, City::find()->count()),
    'coordinates' => $faker->address,
    'budget' => $faker->numberBetween(500, 5000),
    'deadline' => $faker->date(),
    'author_id' => $faker->numberBetween(1, User::find()->count()),
    'performer_id' => $faker->numberBetween(1, User::find()->count()),
    'status_id' => $faker->numberBetween(1, Status::find()->count()),
    'date_add' => $faker->iso8601(),
];
