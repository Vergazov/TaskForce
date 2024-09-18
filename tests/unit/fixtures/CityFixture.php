<?php

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;
use app\models\City;

class CityFixture extends ActiveFixture
{
    public $modelClass = City::class;
}