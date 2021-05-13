<?php

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
            ['access' => 'admin','description' => 'Права администратора']
        );
        $this->insert(
            'roles',
            ['name' => 'Администратор']
        );
        /* Пользователи к ролям */
        $this->createTable(
            'users_to_role',
            [
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
            ['user_id' => 1, 'role_id' => 1]
        );

        /* Роли к доступам */
        $this->createTable(
            'role_to_access',
            [
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
        $this->insert(
            'role_to_access',
            ['role_id' => 1, 'access_id' => 1]
        );
    }

    public function safeDown()
    {
        $this->dropTable('access');
        $this->dropTable('roles');
        $this->dropTable('users_to_role');
        $this->dropTable('role_to_access');
    }
}
