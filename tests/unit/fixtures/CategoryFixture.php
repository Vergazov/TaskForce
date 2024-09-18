<?php

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;
use app\models\Category;

class CategoryFixture extends ActiveFixture
{
    public $modelClass = Category::class;
}