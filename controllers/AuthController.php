<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AuthController extends Controller
{
    public function actionIndex()
    {
        $user = new User;
        return $this->render('index', ['user' => $user]);
    }

    public function actionLogin()
    {
        $loginForm = new LoginForm();

        if (\Yii::$app->request->isPost) {

            $loginForm->load(\Yii::$app->request->post(), 'User');

            if(Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->validate()) {
                $user = $loginForm->user;
                \Yii::$app->user->login($user);
                return $this->redirect('?r=task/index');
            }
        }

    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect('?r=landing');
    }

    public function actionProfile()
    {
            if($id = \Yii::$app->user->getId()) {
            $user = User::findOne($id);

        }

    }

}
