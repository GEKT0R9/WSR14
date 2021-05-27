<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use app\assets\FormAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
FormAsset::register($this);
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <?=
    $form->field($model, 'username')->textInput([
        'autofocus' => true,
        'placeholder' => 'Введите логин',
        'class' => 'input',
        'maxlength' => 50
    ])->label(false)->error(false) ?>

    <?=
    $form->field($model, 'password')->passwordInput([
        'placeholder' => 'Введите пароль',
        'class' => 'input',
        'maxlength' => 256
    ])->label(false)->error(false) ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <?= $form->errorSummary($model, ['class' => 'error']); ?>

    <?= Html::submitButton('Войти') ?>
    <p class="no_ak">Нет аккаунта?</p>
    <?= Html::a('Зарегистрироваться', 'registration', ['class' => 'butt']) ?>


    <?php ActiveForm::end(); ?>

</div>
