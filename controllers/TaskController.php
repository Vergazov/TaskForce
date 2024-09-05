<?php

namespace app\controllers;

use app\models\Category;
use app\models\Task;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;

class TaskController extends Controller
{
    public function actionIndex()
    {
        $task = new Task;
        $task->load(Yii::$app->request->post());

        $taskQuery = $task->getSearchQuery();
        $countQuery = clone $taskQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 15]);
        $tasks = $taskQuery->offset($pages->offset)->limit($pages->limit)->all();

        $categories = Category::find()->all();

        return $this->render('index', ['tasks' => $tasks, 'pages' => $pages, 'categories' => $categories, 'task' => $task]);
    }

    public function actionView($id)
    {
        return $this->render('view', []);
    }
}