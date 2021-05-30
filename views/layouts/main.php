<?php

/* @var $this View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => "Сделаем город лучше!",
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'header',
        ],
    ]);
    $items = [];
    if (!Yii::$app->user->isGuest) {
        $dir_items = [];
        if (Yii::$app->user->identity->isAvailable('dir_status_type')) {
            $dir_items[] = ['label' => 'Статусы', 'url' => ['/directory', 'type' => 'status']];
        }
        if (Yii::$app->user->identity->isAvailable('dir_criteria')) {
            $dir_items[] = ['label' => 'Критерии', 'url' => ['/directory', 'type' => 'criterion']];
        }
        if (Yii::$app->user->identity->isAvailable('dir_access')) {
            $dir_items[] = ['label' => 'Доступы', 'url' => ['/directory', 'type' => 'access']];
        }
        if (count($dir_items) > 0) {
            $items[] = [
                'label' => 'Справочники',
                'items' => $dir_items,
                'options' => [
                    'class' => 'drop-list',
                ],
            ];
        }

        $settings_items = [];
        if (Yii::$app->user->identity->isAvailable('settings_roles')) {
            $settings_items[] = ['label' => 'Роли', 'url' => ['/settings/roles']];
        }
        if (Yii::$app->user->identity->isAvailable('settings_status_process')) {
            $settings_items[] = ['label' => 'Бизнесс процесс', 'url' => ['/settings/status']];
        }
        if (Yii::$app->user->identity->isAvailable('settings_users')) {
            $settings_items[] = ['label' => 'Пользователи', 'url' => ['/settings/users']];
        }
        if (count($settings_items) > 0) {
            $items[] = [
                'label' => 'Настройки',
                'items' => $settings_items,
                'options' => [
                    'class' => 'drop-list',
                ],
            ];
        }
    }
    $items[] = Yii::$app->user->isGuest
        ? (['label' => 'Авторизация', 'url' => ['/main/login']])
        : (Yii::$app->user->identity->isAvailable('request_in_work')
            ? ['label' => Yii::$app->user->identity->username,
                'items' => [
                    ['label' => 'Профиль', 'url' => ['/profile']],
                    ['label' => 'Заявки в работе', 'url' => ['/profile/request-in-work']]
                ],
                'options' => [
                    'class' => 'drop-list',
                ],
            ]
            : ['label' => Yii::$app->user->identity->username, 'url' => ['/profile']]);
    echo Nav::widget([
        'options' => ['class' => 'ul'],
        'items' => $items,
    ]);
    NavBar::end();
    ?>


    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
    <?= $content ?>

</div>

<footer>
    <p>2021 год, NoFantasy/4 все права защищены©</p>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
