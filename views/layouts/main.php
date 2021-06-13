<?php

/* @var $this View */

/* @var $content string */

use app\widgets\Alert;
use yii\bootstrap\Modal;use yii\helpers\Html;
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

    if (Yii::$app->user->isGuest) {
        $items[] = ['label' => 'Авторизация', 'url' => ['/main/login']];
    } else {
        $inner_items = [];
        $inner_items[] = ['label' => 'Профиль', 'url' => ['/profile/index']];
        $inner_items[] = ['label' => 'Ваши заявки', 'url' => ['/profile/requests']];
        if (Yii::$app->user->identity->isAvailable('request_in_work')) {
            $inner_items[] = ['label' => 'Заявки в работе', 'url' => ['/profile/request-in-work']];
        }
        $inner_items[] = ('<li><a data-toggle="modal" data-target="#exit-modal">Выход</a></li>');
        $items[] = [
            'label' => Yii::$app->user->identity->username,
            'items' => $inner_items,
            'options' => [
                'class' => 'drop-list',
            ],
        ];
    }

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
    <? $modal = Modal::begin([
        'id' => 'exit-modal',
        'header' => '<h2>Вы точно хотите выйти?</h2>',
    ]); ?>
    <div class="yes_no">
    <?= Html::beginForm(['/main/logout'], 'post') ?>
    <?= Html::submitButton('Да', ['class' => 'yes_button bton']) ?>
    <?= Html::endForm() ?>
    <button class="no_button bton" data-dismiss="modal">Нет</button>
    <?php $modal->end(); ?>
    </div>
</div>

<footer>
    <p>2021 год, NoFantasy/4 все права защищены©</p>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
