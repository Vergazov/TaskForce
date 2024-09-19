<?php

namespace app\controllers;

use app\models\User;
use yii\web\NotFoundHttpException;

class UserController extends \yii\web\Controller
{
    public function actionView($id)
    {
        if(!$id){
            throw new NotFoundHttpException();
        }

        $user = User::find()
            ->with('specializations','feedback')
            ->where(['id' => $id])
            ->one();

        if(!$user){
            throw new NotFoundHttpException('User not found');
        }

        return $this->render('view', ['user' => $user]);
    }

}
