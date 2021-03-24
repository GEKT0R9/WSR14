<?php

namespace app\repository;

use app\entity\DirCriterion;
use app\entity\DirStatus;

class DirRepository
{
    public static function getStatusAsArray() {
        $status = [];
        foreach (DirStatus::find()->asArray()->all() as $key => $value) {
            $status[$value['id']] = $value['status'];
        }
        return $status;
    }

    public static function getCriterionAsArray() {
        $criterion = [];
        foreach (DirCriterion::find()->asArray()->all() as $key => $value) {
            $criterion[$value['id']] = $value['criterion'];
        }
        return $criterion;
    }

    public static function getFindStatus() {
        return DirStatus::find();
    }

    public static function getFindCriterion() {
        return DirCriterion::find();
    }

    public static function getCreateCriterion($criterion) {
        $new_row = new DirCriterion;
        $new_row->criterion = $criterion;
        $new_row->save();
        return $new_row;
    }

    public static function getCreateStatus($status) {
        $new_row = new DirStatus;
        $new_row->status = $status;
        $new_row->save();
        return $new_row;
    }
}