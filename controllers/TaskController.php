<?php

namespace app\controllers;

use app\models\Category;
use app\models\Feedback;
use app\models\Response;
use app\models\Task;
use app\models\TaskFile;
use app\models\TaskStatus;
use AvailableActions\CancelAction;
use AvailableActions\CompleteAction;
use AvailableActions\DenyAction;
use AvailableActions\RespondAction;
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
        $feedback = new Feedback;
        $response = new Response;

        if (!$id) {
            throw new NotFoundHttpException();
        }
        $task = Task::find()
            ->with('category', 'status', 'responses.user')
            ->where(['id' => $id])
            ->one();

        if (!$task) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['task' => $task, 'feedback' => $feedback, 'response' => $response]);
    }

    public function actionCreate()
    {
        $categories = Category::find()->all();
        $task = new Task;
        $taskFile = new TaskFile;

        if (!Yii::$app->session->has('task_uid')) {
            Yii::$app->session->set('task_uid', uniqid('upload_', true));
        }

        return $this->render('create', ['categories' => $categories, 'task' => $task, 'taskFile' => $taskFile]);
    }

    public function actionStore()
    {
        $categories = Category::find()->all();
        $user = Yii::$app->user->identity;
        $errors = '';

        if (Yii::$app->request->isPost) {
            $task = new Task();
            $task->load(Yii::$app->request->post());
            $task->loadDefaultValues();
            $task->author_id = $user->id;
            $task->task_uid = Yii::$app->session->get('task_uid');

            if ($task->validate()) {
                $task->save();
                if ($task->id) {
                    Yii::$app->session->remove('task_uid');
                }
            } else {
                $errors = $task->getErrors();
            }

            if ($errors) {
                return $this->render('create', ['errors' => $errors, 'task' => $task, 'categories' => $categories]);
            }

            return $this->redirect(Url::to(['task/view', 'id' => $task->id]));
        }

    }

    public function actionUpload()
    {
        if (Yii::$app->request->isPost) {
            $model = new TaskFile;
            $model->task_uid = Yii::$app->session->get('task_uid');
            $model->file = UploadedFile::getInstanceByName('file');
            $model->dt_add = date('Y-m-d H:i:s');

            $model->upload();

            return $this->asJson($model->getAttributes());
        }
    }

    public function actionAcceptPerformer($id): \yii\web\Response
    {
        $response = Response::findOne($id);

        if ($response) {
            $task = $response->task;

            $response->is_accepted = true;
            $response->is_denied = false;
            $response->save();

            $task->performer_id = $response->user_id;
            $task->status_id = TaskStatus::STATUS_IN_PROGRESS;
            $task->save();
        }

        return $this->redirect(Url::to(['task/view', 'id' => $task->id]));
    }

    public function actionDenyPerformer($id): \yii\web\Response
    {
        $response = Response::findOne($id);

        if ($response) {
            $response->is_denied = true;
            $response->is_accepted = false;
            $response->save();
        }

        return $this->redirect(Url::to(['task/view', 'id' => $response->task_id]));
    }

    public function actionDenyTask($id)
    {
        $task = Task::findOne($id);
        if ($task) {
            $task->goToNextStatus(new DenyAction());

            $performer = $task->performer;
            $performer->increaseFailCount();
        }

        return $this->redirect(Url::to(['task/view', 'id' => $task->id]));

    }

    public function actionCancelTask($id)
    {
        $task = Task::findOne($id);
        if ($task) {
            $task->goToNextStatus(new CancelAction());
        }

        return $this->redirect(Url::to(['task/view', 'id' => $task->id]));
    }

    public function actionCompleteTask($id)
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $feedBack = new FeedBack;
            $feedBack->load($post);
            $task =  Task::findOne($id);

            if ($task) {
                $feedBack->task_id = $task->id;
                $feedBack->user_id = $task->author_id;
                $feedBack->dt_add = date('Y-m-d H:i:s');

                if($feedBack->validate()) {
                    $task->goToNextStatus(new CompleteAction());
                    $feedBack->save();
                }
            }
        }

        return $this->redirect(['task/view', 'id' => $id]);
    }

    public function actionResponseTask($id)
    {
        if (Yii::$app->request->isPost) {

            $post = Yii::$app->request->post();
            $response = new Response;
            $response->load($post);
            $task = Task::findOne($id);

            if ($task) {
                $response->task_id = $task->id;
                $response->user_id = Yii::$app->user->identity->id;
                $response->dt_add = date('Y-m-d H:i:s');
                if ($response->validate()) {
                    $task->goToNextStatus(new RespondAction());
                    $response->save(false);
                }
            }
        }

        return $this->redirect(['task/view', 'id' => $id]);
    }

    public function actionChangeStatus($id)
    {
        $task = Task::findOne($id);
        if ($task) {
            $task->status_id = 1;
            $task->performer_id = null;
            $task->save(false);
        }

        return $this->redirect(Url::to(['task/view', 'id' => $id]));
    }

}