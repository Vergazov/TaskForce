<?php

namespace app\controllers;

use app\models\City;
use app\models\Task;
use app\models\User;
use yii\web\Controller;

class TaskController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()
            ->where(['status_id' => 1])
            ->with('category', 'city', 'author', 'performer')
            ->all();

        return $this->render('index', ['tasks' => $tasks]);
    }

}