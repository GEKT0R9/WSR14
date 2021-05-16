<?php

namespace app\models;

use yii\base\Model;

class UserChangeRoleForm extends Model
{
    public $role;

    public function attributeLabels()
    {
        return [
            'role' => 'Роль',
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['role', 'default'],
        ];
    }
}