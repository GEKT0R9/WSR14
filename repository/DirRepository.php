<?php

namespace app\repository;

use app\entity\Access;
use app\entity\DirCriterion;
use app\entity\DirStatusType;
use app\entity\StatusOrder;
use yii\db\ActiveQuery;

class DirRepository
{
    /**
     * Получения статусов в виде массива
     * @return array
     */
    public static function getStatusAsArray() {
        $status = [];
        foreach (StatusOrder::find()->asArray()->all() as $key => $value) {
            $status[$value['id']] = $value['title'];
        }
        return $status;
    }

    /**
     * Получение критериев в виде массива
     * @return array
     */
    public static function getCriterionAsArray() {
        $criterion = [];
        foreach (DirCriterion::find()->asArray()->all() as $key => $value) {
            $criterion[$value['id']] = $value['criterion'];
        }
        return $criterion;
    }

    /**
     * Получения таблицы статусов
     * @return ActiveQuery
     */
    public static function getFindStatus() {
        return DirStatusType::find();
    }

    /**
     * Получение таблици критериев
     * @return ActiveQuery
     */
    public static function getFindCriterion() {
        return DirCriterion::find();
    }

    /**
     * Получение таблици доступов
     * @return ActiveQuery
     */
    public static function getFindAccess() {
        return Access::find();
    }

    /**
     * Созадние критерия
     * @param string $criterion название критерия
     * @return DirCriterion
     */
    public static function getCreateCriterion($criterion) {
        $new_row = new DirCriterion;
        $new_row->criterion = $criterion;
        $new_row->save();
        return $new_row;
    }

    /**
     * Создание статуса
     * @param string $status название статуса
     * @return DirStatusType
     */
    public static function getCreateStatus($status) {
        $new_row = new DirStatusType;
        $new_row->title = $status;
        $new_row->save();
        return $new_row;
    }
}