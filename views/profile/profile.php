<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use app\assets\ProfileAsset;
use yii\helpers\Html;

$this->title = 'Профиль';
ProfileAsset::register($this);
?>

<div class="content">
    <div class="user_info">
        <div class="user_img">
            <p><?= $first_letter ?></p>
        </div>

        <h3 class="fio"><?= $fio ?></h3>
        <?= Html::a(
            'Редактировать',
            ['settings/user-edit', 'id' => Yii::$app->user->id],
            ['class' => 'edit']
        ) ?>
        <p class="mail"><?= $email ?></p>

        <div class="exit">
            <?= Html::beginForm(['/main/logout'], 'post') ?>
            <?= Html::submitButton('выйти') ?>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>