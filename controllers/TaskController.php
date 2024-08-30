<?php

namespace app\controllers;

use app\models\Task;
use yii\web\Controller;

class TaskController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()
            ->where(['status' => 1])
            ->with('category', 'city', 'author', 'performer')
            ->all();

        return $this->render('index', ['tasks' => $tasks]);
    }

}