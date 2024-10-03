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

        return $this->render('create', ['categories' => $categories, 'task' => $task, 'taskFile' => $taskFile]);
    }

    public function actionStore()
    {
        $user = Yii::$app->user->identity;
        $id = '';
        $errors = '';

        if(!Yii::$app->session->has('task_id')) {
            Yii::$app->session->set('task_id', uniqid('upload', true));
        }

        if(Yii::$app->request->isPost){
            $task = new Task();
            $task->load(Yii::$app->request->post());
            $task->loadDefaultValues();
            $task->author_id = $user->id;

            if($task->validate()){
                $task->save();
                $id = Yii::$app->db->getLastInsertID();
            }else{
                $errors = $task->getErrors();
            }

//            $taskFile = new TaskFile;
//            $taskFile->path = UploadedFile::getInstances($taskFile, 'path');
//            if($taskFile->validate()){
//                $taskFile->upload($id);
//            }else {
//                $errors = $taskFile->getErrors();
//            }

            /**
             * TODO: сделать нормальный вывод ошибок
             */
//            if($errors){
//                return $this->render('create', ['errors' => $errors, 'task' => $task, 'taskFile' => $taskFile]);
//            }

            if($errors){
                return $this->render('create', ['errors' => $errors, 'task' => $task]);
            }

            return $this->redirect(Url::to(['task/view', 'id' => $id, 'task' => $task]));
        }

    }

    public function actionUpload()
    {
        if(Yii::$app->request->isPost){
            $model = new TaskFile;
            $model->task_id = Yii::$app->session->get('task_id');
            $model->path = UploadedFile::getInstanceByName('file');

            $model->upload1();

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