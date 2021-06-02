<?php

namespace app\controllers;

use app\entity\Access;
use app\models\DirectoryForm;
use app\repository\DirRepository;
use app\repository\FileRepository;
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

    public function actionImg($id)
    {
        $file = FileRepository::getFileById($id);
        header("Content-type: " . $file->permission);
        print stream_get_contents($file->file_content);
        exit;
    }

    /**
     * Главная страница для справочников
     * @param string $type определяет таблицу для справочника
     * @return string|void|Response
     */
    public function actionIndex($type = null)
    {
        if (Yii::$app->user->isGuest) {
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
                if (!Yii::$app->user->identity->isAvailable('dir_criteria')) {
                    return $this->goHome();
                }
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
                if (!Yii::$app->user->identity->isAvailable('dir_status_type')) {
                    return $this->goHome();
                }
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
                if (!Yii::$app->user->identity->isAvailable('dir_access')) {
                    return $this->goHome();
                }
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
                return $this->goHome();
                break;
        }
        $model = new DirectoryForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            switch ($type) {
                case 'criterion':
                    if (!Yii::$app->user->identity->isAvailable('dir_add_criteria')) {
                        return $this->goHome();
                    }
                    DirRepository::getCreateCriterion($model->text);
                    break;
                case 'status':
                    if (!Yii::$app->user->identity->isAvailable('dir_add_status')) {
                        return $this->goHome();
                    }
                    DirRepository::getCreateStatus($model->text);
                    break;
                default:
                    return $this->goHome();
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
            'title' => 'Справочник "' . $title . '"',
            'button_text' => mb_strtolower($title)
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
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        switch ($table) {
            case 'criterion':
                if (!Yii::$app->user->identity->isAvailable('dir_del_criteria')) {
                    return $this->goHome();
                }
                $query = DirRepository::getFindCriterion();
                $query->where(['id' => $id])->one()->delete();
                Access::find()->where(['access' => 'criterion_' . $id])->one()->delete();
                break;
            case 'status':
                if (!Yii::$app->user->identity->isAvailable('dir_del_status_type')) {
                    return $this->goHome();
                }
                $query = DirRepository::getFindStatus();
                $query->where(['id' => $id])->one()->delete();
                break;
            default:
                return $this->goHome();
                break;
        }
        return $this->redirect(Url::to(['/directory', 'type' => $table]));
    }
}