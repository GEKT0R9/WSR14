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
            'dir_status_type',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(50)->notNull(),
                'description' => $this->string(200)->notNull(),
            ]
        );
        $this->insert(
            'dir_status_type',
            [
                'title' => 'new',
                'description' => 'Может быть только один! Тип статусов заявки которые были только что созданны'
            ]
        );
        $this->insert(
            'dir_status_type',
            [
                'title' => 'in progress',
                'description' => 'Может быть несколько. Тип статусов заявки которые находяться в процессе проверки'
            ]
        );
        $this->insert(
            'dir_status_type',
            [
                'title' => 'performance',
                'description' => 'Может быть только один! Тип статусов заявки которые можно решить'
            ]
        );
        $this->insert(
            'dir_status_type',
            [
                'title' => 'complete',
                'description' => 'Может быть только один! Тип статусов заявки которые были решены'
            ]
        );
        $this->insert(
            'dir_status_type',
            [
                'title' => 'denied',
                'description' => 'Может быть только один! Тип статусов заявки которые были отклонены'
            ]
        );


        $this->createTable(
            'status_order',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(50)->notNull(),
                'description' => $this->string(50),
                'type_id' => $this->integer()->notNull(),
                'order' => $this->integer()->notNull(),
            ]
        );

        $this->addForeignKey(
            'status_id_fk_requests',
            'requests',
            'status_id',
            'status_order',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'type_id_fk_status_order',
            'status_order',
            'type_id',
            'dir_status_type',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->insert(
            'status_order',
            [
                'title' => 'Новая',
                'description' => 'Только что созданная заявка',
                'type_id' => 1,
                'order' => 1
            ]
        );

        $this->insert(
            'status_order',
            [
                'title' => 'На обработке',
                'description' => 'Заявка на этапе проверки',
                'type_id' => 2,
                'order' => 2
            ]
        );
        $this->insert(
            'status_order',
            [
                'title' => 'Выполнение',
                'description' => 'Заявка на этапе выполнения',
                'type_id' => 3,
                'order' => 3
            ]
        );
        $this->insert(
            'status_order',
            [
                'title' => 'Решена',
                'description' => 'Заявка выполнена',
                'type_id' => 4,
                'order' => 4
            ]
        );
        $this->insert(
            'status_order',
            [
                'title' => 'Отклонено',
                'description' => 'Заявка не была принята или отклонена',
                'type_id' => 5,
                'order' => 5
            ]
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

        $this->createTable(
            'request_to_criterion',
            [
                'request_id' => 'int',
                'criterion_id' => 'int',
            ]
        );
        $this->addForeignKey(
            'request_id_fk_request_to_criterion',
            'request_to_criterion',
            'request_id',
            'requests',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'criterion_id_fk_request_to_criterion',
            'request_to_criterion',
            'criterion_id',
            'dir_criterion',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**`
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('status_id_fk_requests', 'requests');
        $this->dropTable('dir_status_type');
        $this->dropTable('status_order');
        $this->dropTable('dir_criterion');
        $this->dropTable('request_to_criterion');
    }
}
