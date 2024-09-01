<?php

namespace tests\unit\fixtures;

use app\models\Category;
use app\models\City;
use app\models\Status;
use yii\test\ActiveFixture;

class TaskFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Task';
    public $depends = [
        'tests\unit\fixtures\CategoryFixture',
        'tests\unit\fixtures\CityFixture',
        'tests\unit\fixtures\UserFixture',
        'tests\unit\fixtures\StatusFixture',
    ];

}