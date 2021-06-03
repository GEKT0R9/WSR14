<?php

namespace app\controllers;

use app\entity\CommentToRequest;
use app\entity\Requests;
use app\entity\RequestToCriterion;
use app\entity\StatusOrder;
use app\models\RegistrationForm;
use app\repository\DirRepository;
use app\repository\RequestRepository;
use app\repository\UserRepository;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\LoginForm;
use yii\web\Response;

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

    /**
     * Главная страница
     * @return string
     */
    public function actionIndex()
    {
        $user_requests_count = 0;
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAvailable('notice')) {
            $status_array = DirRepository::getStatusAsArray();
            $sts = [];
            foreach ($status_array as $key => $val) {
                if (Yii::$app->user->identity->isAvailable('status_' . $key)) {
                    $sts[] = $key;
                }
            }

            $criterion_array = DirRepository::getCriterionAsArray();
            $ctc = [];
            foreach ($criterion_array as $key => $val) {
                if (Yii::$app->user->identity->isAvailable('criterion_' . $key)) {
                    $ctc[] = $key;
                }
            }
            $rtc = RequestToCriterion::find()
                ->select('request_id')
                ->where(['criterion_id' => $ctc])
                ->groupBy('request_id')
                ->all();
            $criterion_req = [];
            foreach ($rtc as $item) {
                $criterion_req[] = $item->request_id;
            }
            $options['id'] = $criterion_req;
            $options['status_id'] = $sts;
            $user_requests_count = RequestRepository::getRequestsFind(['and', $options])->count();

        }
        $request = RequestRepository::getFourRequestsForMain();
        foreach ($request as $key => $value) {
            $ctr = CommentToRequest::find()->where(['request_id' => $value['id']])->all();
            $comment = [];
            foreach ($ctr as $key2 => $value2) {
                $comment[] = [
                    'text' => $value2->comment,
                    'date' => $value2->date,
                    'role' => $value2->user->roles[0]->name
                ];
            }
            $request[$key]['comments'] = $comment;
        }
        return $this->render('index', [
            'requests' => $request,
            'count' => Requests::find()
                ->where(['status_id' => StatusOrder::find()->where(['type_id' => 4])->one()->id])
                ->count(),
            'notice_count' => $user_requests_count,
        ]);
    }

    /**
     * Вывод количества решённых заявок
     * @return string
     */
    public function actionCountResolvRequest()
    {
        return RequestRepository::getCountRequests([
            'status_id' => StatusOrder::find()->where(['type_id' => 4])->one()->id
        ]);
    }

    /**
     * Страница "авторизация"
     * @return string|Response
     */
    public function actionAllRequests()
    {
        $db_requests = RequestRepository::getRequestsFind(['status_id' => 4]);
        $pages = new Pagination(['totalCount' => $db_requests->count(), 'pageSize' => 10]);
        $db_requests = $db_requests->offset($pages->offset)->limit($pages->limit)->all();

        $requests = [];
        foreach ($db_requests as $key => $value) {
            $criteria = [];
            foreach ($value->criteria as $item) {
                $criteria[] = $item->criterion;
            }
            $requests[$key]['id'] = $value->id;
            $requests[$key]['title'] = $value->title;
            $requests[$key]['description'] = $value->description;
            $requests[$key]['criterion'] = implode(', ', $criteria);
            $requests[$key]['type_id'] = $value->status->type_id;
            $requests[$key]['status'] = $value->status->title;
            $requests[$key]['date'] = date('d.m.Y', strtotime($value->date));
            $requests[$key]['before_img'] = $value->before_img_id;
            $requests[$key]['after_img'] = $value->after_img_id;

            $ctr = CommentToRequest::find()->where(['request_id' => $value['id']])->all();
            $comment = [];
            foreach ($ctr as $key2 => $value2) {
                $comment[] = [
                    'text' => $value2->comment,
                    'date' => $value2->date,
                    'role' => $value2->user->roles[0]->name
                ];
            }
            $requests[$key]['comments'] = $comment;
        }

        return $this->render('all-requests', [
            'requests' => $requests,
            'pages' => $pages,
        ]);
    }

    /**
     * Страница "авторизация"
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('/profile/requests');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Страница "выход"
     * log out пользователя
     * Переадресация
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Страница "Регистрациия"
     * @return string|Response
     */
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
            return $this->redirect('/profile/requests');
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }
}
