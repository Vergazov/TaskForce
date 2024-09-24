<?php

namespace app\controllers;

use app\models\User;

class LandingController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $user = new User;
        $this->layout = false;
        return $this->render('index', ['user' => $user]);
    }

}
