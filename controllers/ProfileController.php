<?php

namespace app\controllers;

use app\models\AcceptRequestForm;
use app\models\CreateRequestForm;
use app\models\ProfileForm;
use app\repository\DirRepository;
use app\repository\FileRepository;
use app\repository\RequestRepository;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class ProfileController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Страница "Профиль"
     * @return string|Response
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('main/login');
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

        if ($user->is_admin != 1) {
            $options['create_user_id'] = Yii::$app->user->id;
        }
        $user_requests = RequestRepository::getRequestsFind(['and', $options]);
        $pages = new Pagination(['totalCount' => $user_requests->count(), 'pageSize' => 10]);
        $user_requests = $user_requests->offset($pages->offset)->limit($pages->limit)->all();
        $status = DirRepository::getStatusAsArray();
        $status[0] = 'Все';
        ksort($status);
        $criterion = DirRepository::getCriterionAsArray();
        $requests = [];
        foreach ($user_requests as $key => $value) {
            $requests[$key]['id'] = $value->id;
            $requests[$key]['title'] = $value->title;
            $requests[$key]['description'] = $value->description;
            $requests[$key]['criterion'] = $criterion[$value->criterion_id];
            $requests[$key]['status'] = $status[$value->status_id];
            $requests[$key]['date'] = date('d.m.Y', strtotime($value->date));
            $requests[$key]['img'] = FileRepository::getContentFileById($value->before_img_id);;
            $requests[$key]['allow'] = ($user->is_admin == 1 && $value->status_id != 2 && $value->status_id != 3);
            $requests[$key]['allow_del'] = ($value->status_id != 2 && $value->status_id != 3);
        }

        return $this->render('index', [
            'fio' => implode(' ', $fio),
            'email' => $user->email,
            'requests' => $requests,
            'first_letter' => mb_substr($fio[0], 0, 1) . mb_substr($fio[1], 0, 1),
            'status' => $status,
            'pages' => $pages,
            'model' => $model,
            'model_accept' => new AcceptRequestForm(),
        ]);
    }

    /**
     * Страница создания заявки
     * @return string|Response
     */
    public function actionCreateRequest()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/main/login');
        }

        $model = new CreateRequestForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->validate()) {

                $img = FileRepository::createFile(
                    $model->image->name,
                    base64_encode(file_get_contents($model->image->tempName)),
                    $model->image->size,
                    $model->image->type
                );

                RequestRepository::createRequest(
                    $model->title,
                    $model->description,
                    $model->criterion,
                    $img->id,
                    null,
                    1,
                    Yii::$app->user->id
                );

                return $this->redirect('/profile');

            }
        }

        return $this->render('create-request', [
            'model' => $model,
            'criterion' => DirRepository::getCriterionAsArray(),
        ]);
    }

    /**
     * Удаление заявки
     * @return string|Response
     */
    public function actionDeleteRequest()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $post = Yii::$app->request->post();
        RequestRepository::deleteOneRequest(['id' => $post['id']]);
        return 'Удаленно';
    }

    /**
     * Установка статуса "решена" заявки
     * доступно только администратору
     * @return Response
     */
    public function actionAcceptRequest()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->is_admin != 1) {
            return $this->goHome();
        }
        $model = new AcceptRequestForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->validate()) {

                $img = FileRepository::createFile(
                    $model->image->name,
                    base64_encode(file_get_contents($model->image->tempName)),
                    $model->image->size,
                    $model->image->type
                );

                $request = RequestRepository::getRequestsFind(['id' => $model->id])->one();
                $request->status_id = 2;
                $request->after_img_id = $img->id;
                $request->save();
            }
        }
        return $this->redirect('/profile');
    }

    /**
     * Установка статуса "Отклонена" заявки
     * доступно только администратору
     * @return string|Response
     */
    public function actionRejectRequest()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->is_admin != 1) {
            return $this->goHome();
        }
        $post = Yii::$app->request->post();
        $request = RequestRepository::getRequestsFind(['id' => $post['id']])->one();
        $request->status_id = 3;
        $request->save();
        return 'Статус изменён';
    }
}