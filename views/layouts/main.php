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
        'brandLabel' => "Сделаем мир лучше!",
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'header',
        ],
    ]);
    $items = [
        Yii::$app->user->isGuest ? (
        ['label' => 'Авторизация', 'url' => ['/main/login']]
        ) : (
        ['label' => 'Профиль', 'url' => ['/profile']]
        )
    ];
    if (Yii::$app->user->identity->is_admin == 1) {
        $items[] = ['label' => 'Статусы', 'url' => ['/directory', 'type' => 'status']];
        $items[] = ['label' => 'Критерии', 'url' => ['/directory', 'type' => 'criterion']];
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

</div>

<footer>
    <p>2021 год, NoFantasy/2 все права защищены©</p>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
