<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица dir_criterion справочник критериев
 * @property int id идентификатор
 * @property string criterion текст критерия
 * @package app\entity
 */
class DirCriterion extends ActiveRecord
{
    public static function className()
    {
        return 'dir_criterion';
    }
}