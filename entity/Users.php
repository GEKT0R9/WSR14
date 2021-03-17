<?php

namespace app\entity;

use yii\db\ActiveRecord;

/**
 * Class Users
 * @property int id
 * @property string last_name
 * @property string first_name
 * @property string middle_name
 * @property string username
 * @property string email
 * @property string password
 * @package app\entity
 */
class Users extends ActiveRecord
{

}