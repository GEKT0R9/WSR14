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
        if (Yii::$app->user->identity->isAvailable('admin')) {
            $items[] = [
                'label' => 'Справочники',
                'items' => [
                    ['label' => 'Статусы', 'url' => ['/directory', 'type' => 'status']],
                    ['label' => 'Критерии', 'url' => ['/directory', 'type' => 'criterion']],
                    ['label' => 'Доступы', 'url' => ['/directory', 'type' => 'access']]
                ]
            ];
            $items[] = [
                'label' => 'Настройки',
                'items' => [
                    ['label' => 'Роли', 'url' => ['/settings/roles']],
                    ['label' => 'Бизнесс процесс', 'url' => ['/settings/status']],
                ]
            ];
//        $items[] = ['label' => 'Заявки', 'url' => ['/directory', 'type' => 'criterion']];
        }
    }
    $items[] = Yii::$app->user->isGuest
        ? (['label' => 'Авторизация', 'url' => ['/main/login']])
        : (['label' => 'Профиль', 'url' => ['/profile']]);
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
