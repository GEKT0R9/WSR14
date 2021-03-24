<?php

namespace app\models;

use app\entity\Users;
use app\repository\UserRepository;
use yii\base\BaseObject;
use yii\web\IdentityInterface;

/**
 * Class User
 * @property int id
 * @property string last_name
 * @property string first_name
 * @property string middle_name
 * @property string username
 * @property string password
 * @property string email
 * @property bool is_admin
 * @package app\models
 */
class User extends BaseObject implements IdentityInterface
{
    public $id;
    public $last_name;
    public $first_name;
    public $middle_name;
    public $username;
    public $password;
    public $email;
    public $is_admin;

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return new static(UserRepository::findOneUser(['id' => $id]));
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return new static(UserRepository::findOneUser(['username' => $username]));
    }

    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return new static(UserRepository::findOneUser(['email' => $email]));
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }
}
