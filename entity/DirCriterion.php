<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Class DirCriterion
 * @property int id
 * @property string criterion
 * @package app\entity
 */
class DirCriterion extends ActiveRecord
{
    public static function className()
    {
        return 'dir_criterion';
    }
}