<?php

namespace app\models;

use yii\base\Model;

class RoleAddEditForm extends Model
{
    public $title;
    public $description;

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'description' => 'Описание',
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['title', 'required', 'message' => 'Заполните название'],
            ['description', 'default'],
        ];
    }
}