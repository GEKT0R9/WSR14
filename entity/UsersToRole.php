<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица users_to_role справочник ролей
 * @property int user_id идентификатор пользователя
 * @property int role_id идентификатор роли
 * @property Users user пользователь
 * @property Roles name роль
 * @package app\entity
 */
class UsersToRole extends ActiveRecord
{

}