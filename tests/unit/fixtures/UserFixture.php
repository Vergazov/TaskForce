<?php

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\User';
    public $depends = [
        'tests\unit\fixtures\CityFixture',
        'tests\unit\fixtures\RoleFixture',
    ];

}