<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица users_to_role справочник ролей
 * @property int id идентификатор пользователя
 * @property int user_id идентификатор пользователя
 * @property int request_id идентификатор роли
 * @property string date идентификатор роли
 * @property string comment идентификатор роли
 * @property Users user пользователь
 * @property Requests request роль
 * @package app\entity
 */
class CommentToRequest extends ActiveRecord
{
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public function getRequest()
    {
        return $this->hasOne(Requests::className(), ['id' => 'request_id']);
    }
}