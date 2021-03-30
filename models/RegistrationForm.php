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


    public function attributeLabels()
    {
        return [
            'accept_personal' => 'Подтверждение на обработку персональных данных',
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['last_name', 'required', 'message' => 'Заполните имя'],
            ['first_name', 'required', 'message' => 'Заполните фамилию'],
            ['email', 'required', 'message' => 'Заполните электронную почту'],
            ['username', 'required', 'message' => 'Заполните логин'],
            ['password', 'required', 'message' => 'Придумайте пароль'],
            ['repeat_password', 'required', 'message' => 'Повторите пароль'],
            ['accept_personal', 'required', 'message' => 'Подтвертиде обработку персональных данных'],
            ['middle_name', 'default'],
            ['accept_personal', 'validateAccept'],
            ['password', 'validatePassword'],
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
            if ($this->password != $this->repeat_password) {
                $this->addError($attribute, 'Пароли не совпадают.');
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
            if (!empty(User::findByUsername($this->username)->username)) {
                $this->addError($attribute, 'Пользователь с таким логином уже существует.');
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
            if (User::findByEmail($this->email)->email) {
                $this->addError($attribute, 'Пользователь с такой почтой уже существует.');
            }
        }
    }

    /**
     * Валидация пользовательского соглашения
     * @param $attribute
     * @param $params
     */
    public function validateAccept($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->accept_personal != true) {
                $this->addError($attribute, 'Вы не приняли пользовательское соглашение.');
            }
        }
    }
}