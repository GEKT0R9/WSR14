<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m210421_143114_UserAccess
 */
class m210421_143114_UserAccess extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'access',
            [
                'id' => $this->primaryKey(),
                'access' => $this->string(50)->notNull(),
                'description' => $this->string(100)->notNull(),
            ]
        );
        $this->createTable(
            'roles',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(50)->notNull(),
                'description' => $this->string(100),
            ]
        );
        $this->insert(
            'access',
            ['access' => 'status_new', 'description' => 'Доступ к управлению статусом "Новая"']
        );
        $this->insert(
            'access',
            ['access' => 'status_2', 'description' => 'Доступ к управлению статусом "На обработке"']
        );
        $this->insert(
            'access',
            ['access' => 'status_3', 'description' => 'Доступ к управлению статусом "Выполнение"']
        );
        $this->insert(
            'access',
            ['access' => 'status_complete', 'description' => 'Доступ к управлению статусом "Решена"']
        );
        $this->insert(
            'access',
            ['access' => 'status_denied', 'description' => 'Доступ к управлению статусом "Отклонено"']
        );


        $this->insert(
            'access',
            ['access' => 'criterion_1', 'description' => 'Доступ к управлению критерием "Другое"']
        );

        $this->insert(
            'roles',
            [
                'name' => 'Пользователь',
                'description' => 'Обычный пользователь который авторизовался на сайте'
            ]
        );
        $this->insert(
            'roles',
            ['name' => 'Администратор']
        );
        /* Пользователи к ролям */
        $this->createTable(
            'users_to_role',
            [
                'id' => $this->primaryKey(),
                'user_id' => 'int',
                'role_id' => 'int',
            ]
        );
        $this->addForeignKey(
            'user_id_fk_users_to_role',
            'users_to_role',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'role_id_fk_users_to_role',
            'users_to_role',
            'role_id',
            'roles',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->insert(
            'users_to_role',
            ['user_id' => 1, 'role_id' => 2]
        );

        /* Роли к доступам */
        $this->createTable(
            'role_to_access',
            [
                'id' => $this->primaryKey(),
                'role_id' => 'int',
                'access_id' => 'int',
            ]
        );
        $this->addForeignKey(
            'role_id_fk_role_to_access',
            'role_to_access',
            'role_id',
            'roles',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'access_id_fk_role_to_access',
            'role_to_access',
            'access_id',
            'access',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable(
            'comment_to_request',
            [
                'id' => $this->primaryKey(),
                'comment' => $this->string(50)->notNull(),
                'date' => $this->dateTime()->defaultValue(new Expression('NOW()')),
                'request_id' => $this->integer()->notNull(),
                'user_id' => $this->integer()->notNull(),
            ]
        );
        $this->addForeignKey(
            'request_id_fk_comment_to_request',
            'comment_to_request',
            'request_id',
            'requests',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'user_id_fk_comment_to_request',
            'comment_to_request',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('access');
        $this->dropTable('roles');
        $this->dropTable('users_to_role');
        $this->dropTable('role_to_access');
        $this->dropTable('comment_to_request');
    }
}
