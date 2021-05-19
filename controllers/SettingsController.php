<?php

namespace app\controllers;

use app\entity\Access;
use app\entity\Roles;
use app\entity\RoleToAccess;
use app\entity\StatusOrder;
use app\entity\Users;
use app\entity\UsersToRole;
use app\models\RoleAddAccessForm;
use app\models\RoleAddEditForm;
use app\models\UserChangeRoleForm;
use app\models\UserEditForm;
use app\repository\RequestRepository;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\StaleObjectException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class SettingsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Главная страница для справочников
     * @return string|void|Response
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Удаление строки из определённого справочника
     * @return string|Response
     */
    public function actionRoles()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_roles')) {
            return $this->goHome();
        }
        $buttons = [];
        $template = '';
        if (Yii::$app->user->identity->isAvailable('settings_roles_edit')) {
            $buttons['edit'] = function ($url, $model, $key) {
                return Html::a(
                    'Редактировать',
                    Url::to(['role-edit', 'id' => $key]),
                    ['class' => 'del']
                );
            };
            $template .= '{edit}';
        }
        if (Yii::$app->user->identity->isAvailable('settings_roles_access')) {
            $buttons['accesses'] = function ($url, $model, $key) {
                return Html::a(
                    'Доступы',
                    Url::to(['accesses', 'id' => $key]),
                    ['class' => 'del']
                );
            };
            $template .= '{accesses}';
        }
        if (Yii::$app->user->identity->isAvailable('settings_roles_del')) {
            $buttons['delete'] = function ($url, $model, $key) {
                return Html::a(
                    'Удалить',
                    Url::to(['delete-role', 'id' => $key]),
                    ['class' => 'del']
                );
            };
            $template .= '{delete}';
        }
        $columns = [
            ['attribute' => 'name', 'label' => 'Название',],
            ['attribute' => 'description', 'label' => 'Описание',],
        ];
        if (!empty($template)) {
            $columns[] = [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => $template,
                'buttons' => $buttons,
            ];
        }

        $provider = new ActiveDataProvider(
            [
                'query' => Roles::find(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );
        return $this->render('roles', [
            'provider' => $provider,
            'columns' => $columns,
        ]);
//        return $this->redirect(Url::to(['/directory', 'type' => $table]));
    }

    public function actionRoleAdd()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_roles_add')) {
            return $this->goHome();
        }
        $model = new RoleAddEditForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $role = new Roles;
            $role->name = $model->title;
            $role->description = $model->description;
            $role->save();
            return $this->redirect(Url::to(['roles']));
        }
        return $this->render('role-add', [
            'model' => $model,
            'title' => 'Создание роли',
            'button_text' => 'Создать'
        ]);
    }

    public function actionAccesses($id)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_roles_access')) {
            return $this->goHome();
        }
        $model = new RoleAddAccessForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            RoleToAccess::deleteAll('role_id = ' . $id);
            if ($model->role_access_list) {
                foreach ($model->role_access_list as $item) {
                    $rta = new RoleToAccess;
                    $rta->role_id = $id;
                    $rta->access_id = $item;
                    $rta->save();
                }
            }
        }
        $role_list = [];
        foreach (Roles::find()->where(['id' => $id])->one()->accesses as $key => $value) {
            $role_list[$value->id] = $value->access;
        }
        $all_list = [];
        $access_info = [];
        foreach (Access::find()->all() as $key => $value) {
            $access_info[$value->id] = [
                'id' => $value->id,
                'title' => $value->access,
                'description' => $value->description,
            ];
            if (!array_key_exists($value->id, $role_list)) {
                $all_list[$value->id] = $value->access;
            }
        }
        return $this->render('access-to-role', [
            'model' => $model,
            'role_list' => $role_list,
            'all_list' => $all_list,
            'access_info' => $access_info,
            'title' => 'Редактирование доступов роли',
            'button_text' => 'Сохранить'
        ]);
//        return $this->redirect(Url::to(['roles']));
    }

    public function actionRoleEdit($id)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_roles_edit')) {
            return $this->goHome();
        }
        $role = Roles::find()->where(['id' => $id])->one();
        $model = new RoleAddEditForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $role->name = $model->title;
            $role->description = $model->description;
            $role->save();
            return $this->redirect(Url::to(['roles']));
        }
        $model->title = $role->name;
        $model->description = $role->description;
        return $this->render('role-add', [
            'model' => $model,
            'title' => 'Редактирование роли',
            'button_text' => 'Сохранить'
        ]);
    }

    public function actionDeleteRole($id)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_roles_del')) {
            return $this->goHome();
        }
        Roles::find()->where(['id' => $id])->one()->delete();
        return $this->redirect(Url::to(['roles']));
    }

    public function actionStatus()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_status_process')) {
            return $this->goHome();
        }
        $buttons = [
            'edit' => function ($url, $model, $key) {
                return Html::a(
                    'Редактировать',
                    Url::to(['status-edit', 'id' => $key]),
                    ['class' => 'del']
                );
            },
            'delete' => function ($url, $model, $key) {
                return Html::a(
                    'Удалить',
                    Url::to(['status-delete', 'id' => $key]),
                    ['class' => 'del']
                );
            },
        ];
        $buttons_order = [
            'up' => function ($url, $model, $key) {
                return Html::a(
                    'Поднять',
                    Url::to(['status-up', 'id' => $key]),
                    ['class' => 'del']
                );
            },
            'down' => function ($url, $model, $key) {
                return Html::a(
                    'Опустить',
                    Url::to(['status-down', 'id' => $key]),
                    ['class' => 'del']
//                    'Опустить', null, ['class' => 'del']
                );
            },
        ];
        $columns = [
            [
                'attribute' => 'order',
                'label' => 'Порядок',
            ],
            [
                'attribute' => 'title',
                'label' => 'Название',
            ],
            [
                'attribute' => 'description',
                'label' => 'Описание',
            ],
            [
                'attribute' => 'type_id',
                'label' => 'Тип',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'template' => '{edit}{delete}',
                'buttons' => $buttons,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Порядок',
                'template' => '{up}{down}',
                'buttons' => $buttons_order,
            ]
        ];


        $provider = new ActiveDataProvider(
            [
                'query' => StatusOrder::find()->orderBy('order'),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );
        return $this->render('status', [
            'provider' => $provider,
            'columns' => $columns,
        ]);
    }

    public function actionStatusAdd()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_status_process')) {
            return $this->goHome();
        }
        $model = new RoleAddEditForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $status = new StatusOrder;
            $status->title = $model->title;
            $status->description = $model->description;
            $status->type_id = 2;
            $status->order = StatusOrder::find()->count() + 1;
            $status->save();

            $access = new Access();
            $access->access = 'status_' . $status->id;
            $access->description = 'Доступ к управлению статусом "' . $status->title . '"';
            $access->save();
            return $this->redirect(Url::to(['status']));
        }
        return $this->render('role-add', [
            'model' => $model,
            'title' => 'Создание дополнительного статуса',
            'button_text' => 'Создать'
        ]);
    }

    public function actionStatusEdit($id)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_status_process')) {
            return $this->goHome();
        }
        $status = StatusOrder::find()->where(['id' => $id])->one();
        $model = new RoleAddEditForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $status->title = $model->title;
            $status->description = $model->description;
            $status->save();
            return $this->redirect(Url::to(['status']));
        }
        $model->title = $status->title;
        $model->description = $status->description;
        return $this->render('role-add', [
            'model' => $model,
            'title' => 'Редактирование статуса',
            'button_text' => 'Сохранить'
        ]);
    }

    public function actionStatusDelete($id)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_status_process')) {
            return $this->goHome();
        }
        StatusOrder::find()->where(['id' => $id])->one()->delete();
        return $this->redirect(Url::to(['status']));
    }

    public function actionStatusUp($id)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_status_process')) {
            return $this->goHome();
        }
        $status = StatusOrder::find()->where(['id' => $id])->one();
        if ($status->order - 1 != 0) {
            $status_up = StatusOrder::find()->where(['order' => $status->order - 1])->one();
            $status_up->order += 1;
            $status_up->save();

            $status->order -= 1;
            $status->save();
        }
        return $this->redirect(Url::to(['status']));
    }

    public function actionStatusDown($id)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_status_process')) {
            return $this->goHome();
        }
        $status = StatusOrder::find()->where(['id' => $id])->one();
        if ($status->order + 1 <= StatusOrder::find()->count()) {
            $status_down = StatusOrder::find()->where(['order' => $status->order + 1])->one();
            $status_down->order -= 1;
            $status_down->save();

            $status->order += 1;
            $status->save();
        }
        return $this->redirect(Url::to(['status']));
    }

    public function actionUsers()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_users')) {
            return $this->goHome();
        }
        Url::remember();
        $users = [];
        $user_find = Users::find();
        $pages = new Pagination(['totalCount' => $user_find->count(), 'pageSize' => 10]);
        $user_find = $user_find->offset($pages->offset)->limit($pages->limit)->all();
        foreach ($user_find as $user) {
            $fio = [];
            $fio[] = $user->last_name;
            $fio[] = $user->first_name;
            if ($user->middle_name) {
                $fio[] = $user->middle_name;
            }
            $users[] = [
                'id' => $user->id,
                'fio' => implode(' ', $fio),
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->roles[0]->name
            ];
        }
        return $this->render('users', [
            'users' => $users,
            'pages' => $pages,
        ]);
    }

    public function actionUserEdit($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (Yii::$app->user->id != $id) {
            if (!Yii::$app->user->identity->isAvailable('settings_users_edit')) {
                return $this->goHome();
            }
        }
        $model = new UserEditForm();
        $user = Users::find()->where(['id' => $id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->last_name = $model->last_name;
            $user->first_name = $model->first_name;
            $user->middle_name = $model->middle_name;
            $user->username = $model->username;
            $user->email = $model->email;
            if (!empty($model->password) && $model->edit_password == '1') {
                $user->password = password_hash($model->password, PASSWORD_DEFAULT);
            }
            $user->save();
            return $this->redirect(Url::previous());
        }
        $model->id = $user->id;
        $model->last_name = $user->last_name;
        $model->first_name = $user->first_name;
        $model->middle_name = $user->middle_name;
        $model->username = $user->username;
        $model->email = $user->email;

        return $this->render('user-edit', [
            'model' => $model
        ]);
    }

    public function actionUserChangeRole($id)
    {
        $model = new UserChangeRoleForm();
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('settings_users_role')) {
            return $this->goHome();
        }
        $utr = UsersToRole::find()->where(['user_id' => $id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $utr->user_id = $id;
            $utr->role_id = $model->role;
            $utr->save();
            return $this->redirect(Url::to(['users']));
        }
        $model->role = $utr->role_id;
        $roles = [];
        foreach (Roles::find()->all() as $role) {
            $roles[$role->id] = $role->name;
        }
        return $this->render('user-change-role', [
            'model' => $model,
            'roles' => $roles
        ]);
    }
}