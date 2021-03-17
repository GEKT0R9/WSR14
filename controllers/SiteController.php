<?php

namespace app\controllers;

use app\entity\Files;
use app\entity\Requests;
use app\entity\Users;
use app\models\CreateRequestForm;
use app\models\RegistrationForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
//        $file = file_get_contents('../asfq.png');
//        return '<img src="data:image/png;base64,'.base64_encode($file).'">';
        return $this->render('index');
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
            $new_user = new Users();
            $new_user->last_name = $model->last_name;
            $new_user->first_name = $model->first_name;
            $new_user->middle_name = $model->middle_name;
            $new_user->username = $model->username;
            $new_user->email = $model->email;
            $password = $model->password;
            $new_user->password = password_hash($password, PASSWORD_DEFAULT);
            $new_user->save();

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

    public function actionProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('login');
        }
        $user = Yii::$app->user->identity;
        $fio = [];
        $fio[] = $user->last_name;
        $fio[] = $user->first_name;
        if ($user->middle_name) {
            $fio[] = $user->middle_name;
        }

        $user_requests = Requests::find()->where(['create_user_id' => Yii::$app->user->id])->all();
//        return var_dump($user_requests);
        $requests = [];
        foreach ($user_requests as $key => $value) {
            $requests[$key]['title'] = $value->title;
            $requests[$key]['description'] = $value->description;
            $requests[$key]['date'] = $value->date;
            $requests[$key]['img'] = Files::find()->where(['id' => $value->before_img_id])->one()->file_content;
        }

        return $this->render('profile', [
            'fio' => implode(' ', $fio),
            'email' => $user->email,
            'requests' => $requests,
        ]);
    }

    public function actionCreateRequest()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('login');
        }

        $model = new CreateRequestForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->validate()) {
//                var_dump($model);

                $img = new Files;
                $img->name = $model->image->name;
                $img->file_content = base64_encode(file_get_contents($model->image->tempName));
                $img->size = $model->image->size;
                $img->permission = $model->image->type;
                $img->save();

                $request = new Requests;
                $request->title = $model->title;
                $request->description = $model->description;
                $request->criterion_id = $model->criterion;
                $request->before_img_id = $img->id;
                $request->status_id = 1;
                $request->create_user_id = Yii::$app->user->id;
                $request->save();

//                $img = Files::find()->where(['id' => 1])->one();
////            var_dump($img);
//                return '<img src="data:image/png;base64,' . $img->file_content . '">';
                return $this->redirect('profile');

            }
        }

        return $this->render('create-request', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
