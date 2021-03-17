<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Profile';
?>
<div class="site-login">
    <h1><?= $fio ?></h1>
    <p><?= $email ?></p>

    <?= Html::a('Создать заявку', 'create-request', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

    <div>
        <?= Html::beginForm(['/site/logout'], 'post') ?>
        <?= Html::submitButton('Выйти', ['class' => 'btn btn-primary']) ?>
        <?= Html::endForm() ?>
    </div>

    <? foreach ($requests as $key => $value) : ?>
        <div>
            <div>
                <?= $value['title'] ?>
            </div>
            <div>
                <?= $value['description'] ?>
            </div>
            <div>
                <?= $value['date'] ?>
            </div>
            <div>
                <img src="data:image/png;base64,<?= $value['img'] ?>">
            </div>
        </div>
    <? endforeach; ?>
</div>