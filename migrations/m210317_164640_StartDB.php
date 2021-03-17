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
                'password' => $this->string(256),
                'is_admin' => $this->integer()->defaultValue(0)
            ]
        );
        $this->createTable(
            'requests',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(100)->notNull(),
                'description' => $this->string(500)->notNull(),
                'criterion_id' => $this->integer(),
                'before_img_id' => $this->integer(),
                'after_img_id' => $this->integer(),
                'date' => $this->dateTime()->defaultValue(new Expression('NOW()')),
                'status_id' => $this->integer()->defaultValue(1),
                'create_user_id' => $this->integer()
            ]
        );
        $this->createTable(
            'files',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(50)->notNull(),
                'file_content' => 'LONGBLOB',
                'size' => $this->integer(),
                'permission' => $this->string(50)->notNull()
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable('users');
        $this->dropTable('requests');
        $this->dropTable('files');
    }
}
