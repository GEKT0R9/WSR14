<?php

namespace app\controllers;

use app\entity\DirCriterion;
use app\entity\DirStatus;
use app\entity\Files;
use app\entity\Requests;
use app\entity\Users;
use app\models\CreateRequestForm;
use app\models\DirectoryForm;
use app\models\RegistrationForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ProfileForm;
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
        $user_requests = Requests::find()->where(['status_id' => 2])->limit(4)->all();

        $status = [];
        foreach (DirStatus::find()->asArray()->all() as $key => $value) {
            $status[$value['id']] = $value['status'];
        }

        $criterion = [];
        foreach (DirCriterion::find()->asArray()->all() as $key => $value) {
            $criterion[$value['id']] = $value['criterion'];
        }

        $requests = [];
        foreach ($user_requests as $key => $value) {
            $requests[$key]['id'] = $value->id;
            $requests[$key]['title'] = $value->title;
            $requests[$key]['description'] = $value->description;
            $requests[$key]['criterion'] = $criterion[$value->criterion_id];
            $requests[$key]['status'] = $status[$value->status_id];
            $requests[$key]['date'] = date('d.m.Y', strtotime($value->date));
            $requests[$key]['before_img'] = Files::find()->where(['id' => $value->before_img_id])->one()->file_content;
            $requests[$key]['after_img'] = Files::find()->where(['id' => $value->after_img_id])->one()->file_content;
        }
        return $this->render('index', [
            'requests' => $requests,
            'count' => Requests::find()->where(['status_id' => 2])->count()
        ]);
    }

    public function actionCountResolvRequest()
    {
        return Requests::find()->where(['status_id' => 2])->count();
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
        $options = [];
        $model = new ProfileForm();
        if ($model->load(Yii::$app->request->post())) {
            $_SESSION['filt'] = $model->filt;
            if ($model->filt != 0) {
                $options['status_id'] = $model->filt;
            }
        } else if (!empty($_SESSION['filt'])) {
            $model->filt = $_SESSION['filt'];
            $options['status_id'] = $_SESSION['filt'];
        }

        $options['create_user_id'] = Yii::$app->user->id;
        $user_requests = Requests::find()->where(['and', $options]);
        $pages = new Pagination(['totalCount' => $user_requests->count(), 'pageSize' => 5]);
        $user_requests = $user_requests->offset($pages->offset)->limit($pages->limit)->all();
        $status = ['Все'];
        foreach (DirStatus::find()->asArray()->all() as $key => $value) {
            $status[$value['id']] = $value['status'];
        }

        $criterion = [];
        foreach (DirCriterion::find()->asArray()->all() as $key => $value) {
            $criterion[$value['id']] = $value['criterion'];
        }

        $requests = [];
        foreach ($user_requests as $key => $value) {
            $requests[$key]['id'] = $value->id;
            $requests[$key]['title'] = $value->title;
            $requests[$key]['description'] = $value->description;
            $requests[$key]['criterion'] = $criterion[$value->criterion_id];
            $requests[$key]['status'] = $status[$value->status_id];
            $requests[$key]['date'] = date('d.m.Y', strtotime($value->date));
            $requests[$key]['img'] = Files::find()->where(['id' => $value->before_img_id])->one()->file_content;
        }

        return $this->render('profile', [
            'fio' => implode(' ', $fio),
            'email' => $user->email,
            'requests' => $requests,
            'first_letter' => mb_substr($fio[0], 0, 1) . mb_substr($fio[1], 0, 1),
            'status' => $status,
            'pages' => $pages,
            'model' => $model,
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

                return $this->redirect('profile');

            }
        }

        return $this->render('create-request', [
            'model' => $model,
        ]);
    }

    public function actionDeleteRequest()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $post = Yii::$app->request->post();
        Requests::find()->where(['id' => $post['id']])->one()->delete();
        return 'Удаленно';
    }

    public function actionDelete($id, $table)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        switch ($table) {
            case 'criterion':
                $query = DirCriterion::find();
                break;
            case 'status':
                $query = DirStatus::find();
                break;
            default:
                return var_dump($table);
                break;
        }
        $query->where(['id' => $id])->one()->delete();
        return $this->redirect(Url::to(['directory', 'type' => $table]));
    }

    public function actionDirectory($type)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->is_admin == 0) {
            return $this->goHome();
        }
        $query = null;
        $columns = [];
        $columns[] = 'id';
        $buttons = [];
        $addRowFor = null;
        switch ($type) {
            case 'criterion':
                $query = DirCriterion::find();
                $columns[] = [
                    'attribute' => 'criterion',
                    'label' => 'Критерий',
                ];
                $buttons['delete'] = function ($url, $model, $key) {
                    return Html::a('Удалить', Url::to(['delete', 'id' => $key, 'table' => 'criterion']));
                };
                break;
            case 'status':
                $query = DirStatus::find();
                $columns[] = [
                    'attribute' => 'status',
                    'label' => 'Статус',
                ];
                $buttons['delete'] = function ($url, $model, $key) {
                    return Html::a('Удалить', Url::to(['delete', 'id' => $key, 'table' => 'status']));
                };
                break;
            default:
                return var_dump($type);
                break;
        }
        $model = new DirectoryForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            switch ($type) {
                case 'criterion':
                    $new_row = new DirCriterion;
                    $new_row->criterion = $model->text;
                    $new_row->save();
                    break;
                case 'status':
                    $new_row = new DirStatus;
                    $new_row->status = $model->text;
                    $new_row->save();
                    break;
            }
        }
        $columns[] = [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            'headerOptions' => ['width' => '80'],
            'template' => '{delete}{link}',
            'buttons' => $buttons,
        ];
        $provider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );
        return $this->render('directory', [
            'model' => $model,
            'provider' => $provider,
            'columns' => $columns,
        ]);
    }
}
