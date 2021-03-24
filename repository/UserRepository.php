<?php

namespace app\repository;

use app\entity\Users;

class UserRepository
{
    public static function createUser(
        $last_name,
        $first_name,
        $middle_name,
        $username,
        $email,
        $password
    ) {
        $new_user = new Users();
        $new_user->last_name = $last_name;
        $new_user->first_name = $first_name;
        $new_user->middle_name = $middle_name;
        $new_user->username = $username;
        $new_user->email = $email;
        $new_user->password = password_hash($password, PASSWORD_DEFAULT);
        $new_user->save();
        return $new_user;
    }

    public static function findOneUser($where) {
        return Users::find()->where($where)->one();
    }
}