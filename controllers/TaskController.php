<?php

namespace app\controllers;

use app\models\Category;
use app\models\Task;
use ErrorException;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TaskController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $task = new Task;
        $task->load(Yii::$app->request->post());

        $taskQuery = $task->getSearchQuery();
        $countQuery = clone $taskQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 15]);
        $tasks = $taskQuery->offset($pages->offset)->limit($pages->limit)->all();

        $categories = Category::find()->all();
        $category = new Category;
        return $this->render('index', ['tasks' => $tasks, 'pages' => $pages, 'categories' => $categories, 'task' => $task, 'category' => $category]);
    }

    public function actionView($id)
    {
        if(!$id){
            throw new NotFoundHttpException();
        }
        $task = Task::find()
            ->with('category','status','responses.user')
            ->where(['id' => $id])
            ->one();

        if(!$task){
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['task' => $task]);
    }

}