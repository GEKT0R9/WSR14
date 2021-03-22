<?php

use yii\db\Migration;

/**
 * Class m210320_172625_DirTables
 */
class m210320_172625_DirTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'dir_status',
            [
                'id' => $this->primaryKey(),
                'status' => $this->string(50)->notNull(),
            ]
        );
        $this->insert(
            'dir_status',
            ['status' => 'Новая',]
        );
        $this->insert(
            'dir_status',
            ['status' => 'Решена',]
        );
        $this->insert(
            'dir_status',
            ['status' => 'Отклонена',]
        );
        $this->createTable(
            'dir_criterion',
            [
                'id' => $this->primaryKey(),
                'criterion' => $this->string(50)->notNull(),
            ]
        );
        $this->insert(
            'dir_criterion',
            ['criterion' => 'Другое',]
        );
    }

    /**`
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('dir_status');
        $this->dropTable('dir_criterion');
    }
}
