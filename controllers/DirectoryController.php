<?php

namespace app\controllers;

use app\models\DirectoryForm;
use app\repository\DirRepository;
use app\repository\RequestRepository;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class DirectoryController extends Controller
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
     * @param string $type определяет таблицу для справочника
     * @return string|void|Response
     */
    public function actionIndex($type)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('admin')) {
            return $this->goHome();
        }
        $query = null;
        $columns = [];
        $columns[] = 'id';
        $buttons = [];
        $addRowFor = false;
        $title = '';
        switch ($type) {
            case 'criterion':
                $addRowFor = true;
                $query = DirRepository::getFindCriterion();
                $title = 'Критерий';
                $columns[] = [
                    'attribute' => 'criterion',
                    'label' => 'Критерий',
                ];
                $buttons['delete'] = function ($url, $model, $key) {
                    return Html::a(
                        'Удалить',
                        Url::to(['delete', 'id' => $key, 'table' => 'criterion']),
                        ['class' => 'del']
                    );
                };
                $columns[] = [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'headerOptions' => ['width' => '80'],
                    'template' => '{delete}{link}',
                    'buttons' => $buttons,
                ];
                break;
            case 'status':
                $query = DirRepository::getFindStatus();
                $title = 'Статус';
                $columns[] = [
                    'attribute' => 'title',
                    'label' => 'Название',
                ];
                $columns[] = [
                    'attribute' => 'description',
                    'label' => 'Описание',
                ];
                break;
            case 'access':
                $query = DirRepository::getFindAccess();
                $title = 'Доступы';
                $columns[] = [
                    'attribute' => 'access',
                    'label' => 'Название',
                ];
                $columns[] = [
                    'attribute' => 'description',
                    'label' => 'Описание',
                ];
                break;
            default:
                return var_dump($type);
                break;
        }
        $model = new DirectoryForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            switch ($type) {
                case 'criterion':
                    DirRepository::getCreateCriterion($model->text);
                    break;
                case 'status':
                    DirRepository::getCreateStatus($model->text);
                    break;
            }
        }

        $provider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );
        return $this->render('index', [
            'model' => $model,
            'provider' => $provider,
            'columns' => $columns,
            'add_row_for' => $addRowFor,
            'title' => 'Справочник "' . $title . '"'
        ]);
    }

    /**
     * Удаление строки из определённого справочника
     * @param int $id Идентификатор строки в справочнике
     * @param string $table название справочника
     * @return void|Response
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id, $table)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAvailable('admin')) {
            return $this->goHome();
        }
        switch ($table) {
            case 'criterion':
                $query = DirRepository::getFindCriterion();
                break;
            case 'status':
                $query = DirRepository::getFindStatus();
                break;
            default:
                return var_dump($table);
                break;
        }
        $query->where(['id' => $id])->one()->delete();
        if ($table == 'criterion') {
            RequestRepository::deleteRequests(['criterion_id' => $id]);
        }
        return $this->redirect(Url::to(['/directory', 'type' => $table]));
    }
}