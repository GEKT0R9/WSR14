<?php

use yii\db\Migration;

/**
 * Class m210516_073902_Accesses
 */
class m210516_073902_Accesses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->insert(
            'access',
            ['access' => 'dir_status_type', 'description' => 'Справочник типов статусов']
        );

        $this->insert(
            'access',
            ['access' => 'dir_criteria', 'description' => 'Справочник критериев']
        );
        $this->insert(
            'access',
            ['access' => 'dir_add_criteria', 'description' => 'Добавление критериев']
        );
        $this->insert(
            'access',
            ['access' => 'dir_del_criteria', 'description' => 'Удаление критериев']
        );

        $this->insert(
            'access',
            ['access' => 'dir_access', 'description' => 'Справочник доступов']
        );


        $this->insert(
            'access',
            ['access' => 'settings_users', 'description' => 'Настройки пользователей']
        );
        $this->insert(
            'access',
            ['access' => 'settings_users_role', 'description' => 'Настройки роли пользователей']
        );
        $this->insert(
            'access',
            ['access' => 'settings_users_edit', 'description' => 'Редактирование пользователей']
        );

        $this->insert(
            'access',
            ['access' => 'settings_roles', 'description' => 'Настройки ролей']
        );
        $this->insert(
            'access',
            ['access' => 'settings_roles_edit', 'description' => 'Редактирование ролей']
        );
        $this->insert(
            'access',
            ['access' => 'settings_roles_add', 'description' => 'Добавление ролей']
        );
        $this->insert(
            'access',
            ['access' => 'settings_roles_del', 'description' => 'Удаление ролей']
        );
        $this->insert(
            'access',
            ['access' => 'settings_roles_access', 'description' => 'Управление доступами ролей']
        );

        $this->insert(
            'access',
            ['access' => 'settings_status_process', 'description' => 'Настройки бизнес процесса']
        );


        $this->insert(
            'access',
            ['access' => 'del_request', 'description' => 'Удаление заявки']
        );

        $this->insert(
            'access',
            ['access' => 'request_in_work', 'description' => 'Заявки в работе']
        );


        $this->insert(
            'access',
            ['access' => 'notice', 'description' => 'Уведомления']
        );


        $this->insert(
            'role_to_access',
            ['role_id' => 1, 'access_id' => 1]
        );

        $this->insert(
            'role_to_access',
            ['role_id' => 1, 'access_id' => 21]
        );
        foreach(\app\entity\Access::find()->all() as $value){
            $this->insert(
                'role_to_access',
                ['role_id' => 2, 'access_id' => $value->id]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210516_073902_Accesses не нуждаеться в откате.\n";
        return false;
    }
}
