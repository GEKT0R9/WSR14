<?php

namespace app\models;

use yii\base\Model;

class RegistrationForm extends Model
{
    public $last_name;
    public $first_name;
    public $middle_name;
    public $email;
    public $username;
    public $repeat_password;
    public $password;
    public $accept_personal;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                [
                    'last_name',
                    'first_name',
                    'email',
                    'username',
                    'repeat_password',
                    'password',
                    'accept_personal'
                ],
                'required'
            ],
            ['middle_name', 'default'],
            ['accept_personal', 'validateAccept'],
            ['password', 'validatePassword'],
            ['username', 'validateUsername'],
            ['email', 'validateEmail'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->password != $this->repeat_password) {
                $this->addError($attribute, 'Пароли не совпадают.');
            }
        }
    }

    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!empty(User::findByUsername($this->username)->username)) {
                $this->addError($attribute, 'Пользователь с таким логином уже существует.');
            }
        }
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (User::findByEmail($this->email)->email) {
                $this->addError($attribute, 'Пользователь с такой почтой уже существует.');
            }
        }
    }

    public function validateAccept($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->accept_personal != true) {
                $this->addError($attribute, 'Вы не приняли пользовательское соглашение.');
            }
        }
    }
}