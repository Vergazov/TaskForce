<?php

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;

class PerformerSpecializationFixture extends ActiveFixture
{
    public $modelClass = 'app\models\PerformerSpecialization';
    public $depends = [
        'tests\unit\fixtures\UserFixture',
        'tests\unit\fixtures\SpecializationFixture',
    ];
}