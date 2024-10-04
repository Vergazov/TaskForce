<?php

namespace app\controllers;

use app\models\Category;
use app\models\Response;
use app\models\Task;
use app\models\TaskFile;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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

    public function actionCreate()
    {
        $categories = Category::find()->all();
        $task = new Task;
        $taskFile = new TaskFile;

        if(!Yii::$app->session->has('task_uid')) {
            Yii::$app->session->set('task_uid', uniqid('upload_', true));
        }

        return $this->render('create', ['categories' => $categories, 'task' => $task, 'taskFile' => $taskFile]);
    }

    public function actionStore()
    {
        $categories = Category::find()->all();
        $user = Yii::$app->user->identity;
        $errors = '';

        if(Yii::$app->request->isPost){
            $task = new Task();
            $task->load(Yii::$app->request->post());
            $task->loadDefaultValues();
            $task->author_id = $user->id;
            $task->task_uid = Yii::$app->session->get('task_uid');

            if($task->validate()){
                $task->save();
                if($task->id){
                    Yii::$app->session->remove('task_uid');
                }
            }else{
                $errors = $task->getErrors();
            }

            if($errors){
                return $this->render('create', ['errors' => $errors, 'task' => $task, 'categories' => $categories]);
            }

            return $this->redirect(Url::to(['task/view', 'id' => $task->id]));
        }

    }

    public function actionUpload()
    {
        if(Yii::$app->request->isPost){
            $model = new TaskFile;
            $model->task_uid = Yii::$app->session->get('task_uid');
            $model->file = UploadedFile::getInstanceByName('file');
            $model->dt_add = date('Y-m-d H:i:s');

            $model->upload();

            return $this->asJson($model->getAttributes());
        }
    }

    public function actionAcceptPerformer($performerId, $taskId)
    {
        $task = Task::findOne($taskId);
        if($task){
            $task->performer_id = $performerId;
            $task->status_id = 3;
            $task->save(false);
        }

        return $this->redirect(Url::to(['task/view', 'id' => $taskId, 'task' => $task]));
    }

    public function actionDenyPerformer($responseId, $taskId)
    {
        $response = Response::findOne($responseId);
        if($response){
            $response->status_id = 1;
            $response->save(false);
        }

        $task = Task::findOne($taskId);

        return $this->redirect(Url::to(['task/view', 'id' => $taskId, 'task' => $task]));
    }

}