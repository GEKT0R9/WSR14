<?php

namespace app\models;

use yii\base\Model;

class RoleAddAccessForm extends Model
{
    public $all_access_list;
    public $role_access_list;

    public function attributeLabels()
    {
        return [
            'all_access_list' => 'Список доступов',
            'role_access_list' => 'Доступы у данной роли',
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['all_access_list', 'default'],
            ['role_access_list', 'default'],
        ];
    }
}