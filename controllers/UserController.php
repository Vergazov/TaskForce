<?php

namespace app\controllers;

use app\models\User;

class UserController extends \yii\web\Controller
{
    public function actionView($id)
    {

        $user = User::find()
            ->with('specializations','feedback')
            ->where(['id' => $id])
            ->one();

//        dd($user);
        return $this->render('view', ['user' => $user]);
    }

}
