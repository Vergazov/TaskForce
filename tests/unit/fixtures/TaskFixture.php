<?php

namespace tests\unit\fixtures;

use app\models\TaskStatus;
use yii\test\ActiveFixture;
use app\models\Task;

class TaskFixture extends ActiveFixture
{
    public $modelClass = Task::class;
    public $depends = [
        CategoryFixture::class,
        CityFixture::class,
        SpecializationFixture::class,
        TaskStatusFixture::class,
        UserStatusFixture::class,
    ];

}