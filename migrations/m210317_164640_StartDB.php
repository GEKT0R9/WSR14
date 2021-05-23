<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m210317_164640_StartDB
 */
class m210317_164640_StartDB extends Migration
{

    public function safeUp()
    {
        $this->createTable(
            'users',
            [
                'id' => $this->primaryKey(),
                'last_name' => $this->string(50)->notNull(),
                'first_name' => $this->string(50)->notNull(),
                'middle_name' => $this->string(50),
                'username' => $this->string(50)->notNull()->unique(),
                'email' => $this->string(50)->notNull()->unique(),
                'password' => $this->string(256)
            ]
        );
        $this->insert(
            'users',
            [
                'last_name' => 'admin',
                'first_name' => 'admin',
                'middle_name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@admin.admin',
                'password' => '$2y$10$VYhgVh19t21sDBJvwlfQmuEk1mu44OfLLqnsVoWwHqHQ3DmlEcd/a',
            ]
        );
        $this->createTable(
            'requests',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(100)->notNull(),
                'description' => $this->string(500)->notNull(),
                'before_img_id' => $this->integer(),
                'after_img_id' => $this->integer(),
                'date' => $this->dateTime()->defaultValue(new Expression('NOW()')),
                'status_id' => $this->integer()->defaultValue(1),
                'create_user_id' => $this->integer()
            ]
        );
        $this->addForeignKey(
            'create_user_id_fk_requests',
            'requests',
            'create_user_id',
            'users',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createTable(
            'files',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(50)->notNull(),
                'file_content' => 'bytea',
                'size' => $this->integer(),
                'permission' => $this->string(50)->notNull()
            ]
        );

        $this->addForeignKey(
            'before_img_id_fk_requests',
            'requests',
            'before_img_id',
            'files',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'after_img_id_fk_requests',
            'requests',
            'after_img_id',
            'files',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('users');
        $this->dropTable('requests');
        $this->dropTable('files');
    }
}
