<?php

namespace app\entity;

use yii\db\ActiveRecord;

/**
 * Таблица пользователей
 * @property int id идентификатор
 * @property string last_name фамилия
 * @property string first_name имя
 * @property string|null middle_name отчетсво
 * @property string username логин пользователя
 * @property string email электронная почта
 * @property string password пароль
 *
 * @property Roles[] roles роли
 * @package app\entity
 */
class Users extends ActiveRecord
{
    public function getRoles()
    {
        return $this->hasMany(Roles::className(), ['id' => 'role_id'])
            ->viaTable('users_to_role',['user_id' => 'id']);
    }
}