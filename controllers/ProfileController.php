<?php

namespace app\controllers;

use app\entity\CommentToRequest;
use app\entity\RequestToCriterion;
use app\entity\StatusOrder;
use app\models\AcceptRequestForm;
use app\models\CreateRequestForm;
use app\models\ProfileForm;
use app\repository\DirRepository;
use app\repository\FileRepository;
use app\repository\RequestRepository;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
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
        Url::remember();
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
        $options['create_user_id'] = Yii::$app->user->id;

        $cri = [];
        $criterion_array = DirRepository::getCriterionAsArray();
        foreach ($criterion_array as $key => $val) {
            $cri[] = $key;
        }

        $model = new ProfileForm();
        if ($model->load(Yii::$app->request->post())) {
            $_SESSION['status_filt'] = $model->status_filt;
            $_SESSION['criteria_filt'] = $model->criteria_filt;
            if ($model->status_filt != 0) {
                $options['status_id'] = $model->status_filt;
            }
            if ($model->criteria_filt != 0) {
                $cri = $model->criteria_filt;
            }
        } else {
            if (!empty($_SESSION['status_filt'])) {
                $model->status_filt = $_SESSION['status_filt'];
                $options['status_id'] = $_SESSION['status_filt'];
            }
            if (!empty($_SESSION['criteria_filt'])) {
                $model->criteria_filt = $_SESSION['criteria_filt'];
                $cri = $_SESSION['criteria_filt'];
            }
        }
        $rtc = RequestToCriterion::find()
            ->select('request_id')
            ->where(['criterion_id' => $cri])
            ->groupBy('request_id')
            ->all();
        $criterion_req = [];
        foreach ($rtc as $item) {
            $criterion_req[] = $item->request_id;
        }
        $options['id'] = $criterion_req;

        $user_requests = RequestRepository::getRequestsFind(['and', $options]);
        $pages = new Pagination(['totalCount' => $user_requests->count(), 'pageSize' => 10]);
        $user_requests = $user_requests->offset($pages->offset)->limit($pages->limit)->all();
        $status = DirRepository::getStatusAsArray();
        $status[0] = 'Все';
        $criterion_array[0] = 'Все';
        ksort($status);
        ksort($criterion_array);
        $requests = [];
        foreach ($user_requests as $key => $value) {
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
            $requests[$key]['before_img'] =
                $value->before_img->file_content?stream_get_contents($value->before_img->file_content):null;
            $requests[$key]['after_img'] =
                $value->after_img->file_content?stream_get_contents($value->after_img->file_content):null;
            $requests[$key]['allow'] = ($value->status->type_id == 1);
            $requests[$key]['allow_del'] = ($user->isAvailable('del_request') && $value->status->type_id == 1);
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
            'criteria' => $criterion_array,
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
                    (StatusOrder::find()->where(['order' => 1])->one())->id,
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
     * Страница создания заявки
     * @return string|Response
     */
    public function actionEditRequest($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/main/login');
        }
        $request = RequestRepository::getRequestsFind(['id' => $id])->one();
        if ($request->create_user_id != Yii::$app->user->id) {
            return $this->goHome();
        }
        $model = new CreateRequestForm();
        if ($model->load(Yii::$app->request->post())) {
            $request->title = $model->title;
            $request->description = $model->description;
            $request->save();
            return $this->redirect('/profile');
        }
        $model->title = $request->title;
        $model->description = $request->description;
        return $this->render('edit-request', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление заявки
     * @return string|Response
     */
    public function actionDeleteRequest()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('del_request')) {
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
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new AcceptRequestForm();
        if ($model->load(Yii::$app->request->post())) {
            $request = RequestRepository::getRequestsFind(['id' => $model->id])->one();
            if (Yii::$app->user->identity->isAvailable('status_' . $request->status->id)) {
                $model->image = UploadedFile::getInstance($model, 'image');
                if ($model->validate()) {

                    $img = FileRepository::createFile(
                        $model->image->name,
                        base64_encode(file_get_contents($model->image->tempName)),
                        $model->image->size,
                        $model->image->type
                    );

                    $request->status_id = StatusOrder::find()->where(['order' => $request->status->order + 1])->one()->id;
                    $request->after_img_id = $img->id;
                    $request->save();
                }
                return $this->redirect('request-in-work');
            }
        }
        return $this->goHome();
    }

    /**
     * Установка статуса "Отклонена" заявки
     * доступно только администратору
     * @return string|Response
     */
    public function actionRejectRequest()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $post = Yii::$app->request->post();
        $request = RequestRepository::getRequestsFind(['id' => $post['id']])->one();
        if (Yii::$app->user->identity->isAvailable('status_' . $request->status->id)) {
            if (!empty($post['criteria'])) {
                RequestToCriterion::deleteAll(['request_id' => $post['id']]);
                foreach ($post['criteria'] as $item) {
                    $rtc = new RequestToCriterion();
                    $rtc->request_id = $post['id'];
                    $rtc->criterion_id = $item;
                    $rtc->save();
                }
            }
            if (!empty($post['comment'])) {
                $ctr = new CommentToRequest();
                $ctr->comment = $post['comment'];
                $ctr->user_id = Yii::$app->user->id;
                $ctr->request_id = $post['id'];
                $ctr->save();
            }
            if ($request->status->order == 2) {
                $request->status_id = StatusOrder::find()->where(['type_id' => 5])->one()->id;
            } else {
                $request->status_id = StatusOrder::find()->where(['order' => $request->status->order - 1])->one()->id;
            }
            $request->save();
            return 'Статус изменён';
        }
        return $this->goHome();
    }

    /**
     * Установка статуса "Отклонена" заявки
     * доступно только администратору
     * @return string|Response
     */
    public function actionStatusUpRequest()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $post = Yii::$app->request->post();
        $request = RequestRepository::getRequestsFind(['id' => $post['id']])->one();
        if (Yii::$app->user->identity->isAvailable('status_' . (($request->status->type_id == 1) ? 'new' : $request->status->id))) {
            if (!empty($post['comment'])) {
                $ctr = new CommentToRequest();
                $ctr->comment = $post['comment'];
                $ctr->user_id = Yii::$app->user->id;
                $ctr->request_id = $post['id'];
                $ctr->save();
            }
            $request->status_id = StatusOrder::find()->where(['order' => $request->status->order + 1])->one()->id;
            $request->save();
            return 'Статус изменён';
        }
        return $this->goHome();
    }

    public function actionRequestInWork()
    {
        if (Yii::$app->user->isGuest && !Yii::$app->user->identity->isAvailable('request_in_work')) {
            return $this->redirect('main/login');
        }

        $options = [];

        $status_array = DirRepository::getStatusAsArray();
        $status = [];
        $sts = [];
        foreach ($status_array as $key => $val) {
            if (Yii::$app->user->identity->isAvailable('status_' . $key)) {
                $sts[] = $key;
                $status[$key] = $val;
            }
        }

        $criterion_array = DirRepository::getCriterionAsArray();

        $cri = [];
        $criter = [];
        foreach ($criterion_array as $key => $val) {
            if (Yii::$app->user->identity->isAvailable('criterion_' . $key)) {
                $cri[] = $key;
                $criter[$key] = $val;
            }
        }

        $options['status_id'] = $sts;
        $model = new ProfileForm();
        if ($model->load(Yii::$app->request->post())) {
            $_SESSION['status_filt'] = $model->status_filt;
            $_SESSION['criteria_filt'] = $model->criteria_filt;
            if ($model->status_filt != 0) {
                $options['status_id'] = $model->status_filt;
            }
            if ($model->criteria_filt != 0) {
                $cri = $model->criteria_filt;
            }
        } else {
            if (!empty($_SESSION['status_filt'])) {
                $model->status_filt = $_SESSION['status_filt'];
                $options['status_id'] = $_SESSION['status_filt'];
            }
            if (!empty($_SESSION['criteria_filt'])) {
                $model->criteria_filt = $_SESSION['criteria_filt'];
                $cri = $_SESSION['criteria_filt'];
            }
        }

        $rtc = RequestToCriterion::find()
            ->select('request_id')
            ->where(['criterion_id' => $cri])
            ->groupBy('request_id')
            ->all();
        $criterion_req = [];
        foreach ($rtc as $item) {
            $criterion_req[] = $item->request_id;
        }
        $options['id'] = $criterion_req;

        $user_requests = RequestRepository::getRequestsFind(['and', $options]);
        $pages = new Pagination(['totalCount' => $user_requests->count(), 'pageSize' => 10]);
        $user_requests = $user_requests->offset($pages->offset)->limit($pages->limit)->all();
        $status[0] = 'Все';
        $criter[0] = 'Все';
        ksort($status);
        ksort($criter);
        $requests = [];
        foreach ($user_requests as $key => $value) {
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
            $requests[$key]['before_img'] = $value->before_img->file_content;
            $requests[$key]['after_img'] = $value->after_img->file_content;
            $requests[$key]['allow'] =
                (Yii::$app->user->identity->isAvailable('status_' . $value->status->id))
                && ($value->status->type_id != 4 || $value->status->type_id != 5);
            $requests[$key]['allow_del'] = (
                Yii::$app->user->identity->isAvailable('del_request')
                && $value->status->type_id == 1
            );
        }

        return $this->render('request-in-work', [
            'requests' => $requests,
            'status' => $status,
            'pages' => $pages,
            'model' => $model,
            'all_criteria' => DirRepository::getCriterionAsArray(),
            'criteria' => $criter,
            'model_accept' => new AcceptRequestForm(),
        ]);
    }
}