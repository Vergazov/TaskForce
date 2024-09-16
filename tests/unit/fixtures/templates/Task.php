<?php

namespace unit\fixtures\templates;

use app\models\Category;
use app\models\City;
use app\models\Status;
use app\models\User;
use Faker\Generator;

/**
 * @var $faker Generator
 */

return [
    'name' => $faker->paragraph(1),
    'description' => $faker->paragraph(1),
    'category_id' => $faker->numberBetween(1, Category::find()->count()),
    'city_id' => $faker->numberBetween(1, City::find()->count()),
    'location' => $faker->address,
    'budget' => $faker->numberBetween(500, 5000),
    'expire_dt' => date('Y-m-d', time() + random_int(86400, 864000)),
    'author_id' => $faker->numberBetween(1, User::find()->count()),
    'performer_id' => $faker->numberBetween(1, User::find()->count()),
    'status_id' => $faker->numberBetween(1, Status::find()->count()),
    'dt_add' => date('Y-m-d H:i:s', time() - random_int(0, 432000))
];
