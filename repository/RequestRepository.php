<?php

namespace app\repository;

use app\entity\Requests;

class RequestRepository
{
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

    public static function getCountRequests($where = null) {
        return Requests::find()->where($where)->count();
    }

    public static function getRequestsFind($where) {
        return Requests::find()->where($where);
    }

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

    public static function deleteOneRequest($where) {
        Requests::find()->where($where)->one()->delete();
    }
}