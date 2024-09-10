<?php

namespace unit\fixtures\templates;

use app\models\Task;
use app\models\User;
use Faker\Generator;

/**
 * @var $faker Generator
 */

return [
    'performer_comment' => $faker->paragraph(1),
    'price' => $faker->numberBetween(500, 5000),
    'task_id' => $faker->numberBetween(1, Task::find()->count()),
    'user_id' => $faker->numberBetween(1, User::find()->count()),
    'creator_comment' => $faker->paragraph(1),
    'rating' => $faker->numberBetween(1, 10),
];
