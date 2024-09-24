<?php

namespace app\controllers;

use app\models\City;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\widgets\ActiveForm;

class RegistrationController extends Controller
{

    public function actionIndex()
    {
        $cities = City::find()->all();
        $user = new User;
        return $this->render('index', ['user' => $user, 'cities' => $cities]);
    }

    public function actionStore()
    {
        $user = new User;
        $cities = City::find()->all();

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $user->load($post);

            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = 'json';

                return ActiveForm::validate($user);
            }

            if($user->validate()){
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
                $user->save(false);
                $this->redirect(['index']);
            }
        }

        return $this->render('index', ['user' => $user, 'cities' => $cities]);
    }

}
