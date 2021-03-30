<?php

namespace app\repository;

use app\entity\Requests;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;

class RequestRepository
{
    /**
     * Вывод 4 последних решённых заявок
     * @return array
     */
    public static function getFourRequestsForMain() {
        $user_requests = Requests::find()
            ->where(['status_id' => 2])
            ->orderBy(['date' => SORT_DESC])
            ->limit(4)
            ->all();

        $status = DirRepository::getStatusAsArray();
        $criterion = DirRepository::getCriterionAsArray();

        $requests = [];
        foreach ($user_requests as $key => $value) {
            $requests[$key]['id'] = $value->id;
            $requests[$key]['title'] = $value->title;
            $requests[$key]['description'] = $value->description;
            $requests[$key]['criterion'] = $criterion[$value->criterion_id];
            $requests[$key]['status'] = $status[$value->status_id];
            $requests[$key]['date'] = date('d.m.Y', strtotime($value->date));
            $requests[$key]['before_img'] = FileRepository::getContentFileById($value->before_img_id);
            $requests[$key]['after_img'] = FileRepository::getContentFileById($value->after_img_id);
        }
        return $requests;
    }

    /**
     * получение кол-ва заявок в таблице по where
     * @param array|null $where запрос
     * @return bool|int|string|null
     */
    public static function getCountRequests($where = null) {
        return Requests::find()->where($where)->count();
    }

    /**
     * получение заявок в таблице по where
     * @param array $where запрос
     * @return ActiveQuery
     */
    public static function getRequestsFind($where) {
        return Requests::find()->where($where);
    }

    /**
     * Создание заявки
     * @param string $title заголовок
     * @param string $description описание
     * @param int $criterion_id идентификатор кримтерия
     * @param int $before_img_id идентификатор фото "до"
     * @param int|null $after_img_id идентификатор фото "после" (Не обязательно)
     * @param int $status_id идентификатор статуса
     * @param int $create_user_id идентификатор пользователя
     * @return Requests
     */
    public static function createRequest(
        $title,
        $description,
        $criterion_id,
        $before_img_id,
        $after_img_id,
        $status_id,
        $create_user_id
    ) {
        $request = new Requests;
        $request->title = $title;
        $request->description = $description;
        $request->criterion_id = $criterion_id;
        $request->before_img_id = $before_img_id;
        $request->after_img_id = $after_img_id;
        $request->status_id = $status_id;
        $request->create_user_id = $create_user_id;
        $request->save();
        return $request;
    }

    /**
     * Удалить одну заявку по where
     * @param array $where запрос
     * @throws Throwable
     * @throws StaleObjectException
     */
    public static function deleteOneRequest($where) {
        Requests::find()->where($where)->one()->delete();
    }

    /**
     * Удалить множество заявок по where
     * @param array $where запрос
     */
    public static function deleteRequests($where) {
        Requests::deleteAll($where);
    }
}