<?php

namespace app\models;

use yii\base\Model;

class UserEditForm extends Model
{
    public $id;
    public $last_name;
    public $first_name;
    public $middle_name;
    public $username;
    public $email;
    public $edit_password;
    public $password;
    public $repeat_password;

    public function attributeLabels()
    {
        return [
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'username' => 'Логин',
            'email' => 'Электронная почта',
            'edit_password' => 'Изменить пароль',
            'password' => 'Пароль',
            'repeat_password' => 'Повтор пароля',
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['id', 'default'],
            ['last_name', 'required', 'message' => 'Введите фамилию'],
            ['first_name', 'required', 'message' => 'Введите имя'],
            ['middle_name', 'default'],
            ['username', 'required', 'message' => 'Введите логин'],
            ['email', 'required', 'message' => 'Введите электронную почту'],
            ['edit_password', 'default'],
            ['password', 'default'],
            ['repeat_password', 'default'],
            ['password', 'validatePassword'],
            ['edit_password', 'validatePassword'],
            ['repeat_password', 'validatePassword'],
            ['username', 'validateUsername'],
            ['email', 'validateEmail'],
        ];
    }

    /**
     * Валидация пароля
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->edit_password == '1'){
                if (empty($this->password)) {
                    $this->addError($attribute, 'Поле для пароля пустое.');
                }
                if (empty($this->repeat_password)){
                    $this->addError($attribute, 'Поле для повторного пароля пустое.');
                }
                if ($this->password != $this->repeat_password) {
                    $this->addError($attribute, 'Пароли не совпадают.');
                }
            }

        }
    }

    /**
     * Валидация логина
     * Логин должен быть уникален
     * @param $attribute
     * @param $params
     */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findByUsername($this->username);
            if (!empty($user->username)) {
                if ($user->id != $this->id) {
                    $this->addError($attribute, 'Пользователь с таким логином уже существует.');
                }
            }
        }
    }

    /**
     * Валидация почты
     * Почта должена быть уникальна
     * @param $attribute
     * @param $params
     */
    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findByEmail($this->email);
            if (!empty($user->email)) {
                if ($user->id != $this->id) {
                    $this->addError($attribute, 'Пользователь с такой почтой уже существует.');
                }
            }
        }
    }
}