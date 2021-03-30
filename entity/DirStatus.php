<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица dir_status справочник критериев
 * @property int id идентификатор
 * @property string status текст статуса
 * @package app\entity
 */
class DirStatus extends ActiveRecord
{
    public static function className()
    {
        return 'dir_status';
    }
}