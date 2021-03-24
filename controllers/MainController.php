<?php

namespace app\controllers;

use app\entity\Requests;
use app\models\RegistrationForm;
use app\repository\RequestRepository;
use app\repository\UserRepository;
use Yii;
use yii\web\Controller;
use app\models\LoginForm;

class MainController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'requests' => RequestRepository::getFourRequestsForMain(),
            'count' => Requests::find()->where(['status_id' => 2])->count()
        ]);
    }

    public function actionCountResolvRequest()
    {
        return RequestRepository::getCountRequests(['status_id' => 2]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionRegistration()
    {
        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $password = $model->password;

            $new_user = UserRepository::createUser(
                $model->last_name,
                $model->first_name,
                $model->middle_name,
                $model->username,
                $model->email,
                $password
            );

            $model = new LoginForm();
            $model->username = $new_user->username;
            $model->password = $password;
            $model->login();
            return $this->goHome();
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }
}
