<?php

namespace unit\fixtures\templates;

use app\models\Task;
use app\models\User;
use Faker\Generator;

/**
 * @var $faker Generator
 */

return [
    'price' => $faker->numberBetween(500, 5000),
    'comment' => $faker->text(20),
    'task_id' => $faker->numberBetween(1, Task::find()->count()),
    'user_id' => $faker->numberBetween(1, User::find()->count()),
    'dt_add' => date('Y-m-d H:i:s', time() - random_int(0, 432000))
];
