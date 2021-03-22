<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Class DirStatus
 * @property int id
 * @property string status
 * @package app\entity
 */
class DirStatus extends ActiveRecord
{
    public static function className()
    {
        return 'dir_status';
    }
}