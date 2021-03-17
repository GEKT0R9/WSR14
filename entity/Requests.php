<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Class Requests
 * @property int id
 * @property string title
 * @property string description
 * @property int criterion_id
 * @property int before_img_id
 * @property int after_img_id
 * @property string date
 * @property int status_id
 * @property int create_user_id
 * @package app\entity
 */
class Requests extends ActiveRecord
{

}