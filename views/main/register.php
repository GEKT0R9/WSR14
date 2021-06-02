<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use app\assets\FormAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';

FormAsset::register($this);
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>


    <?=
    $form->field($model, 'last_name')
        ->textInput(['autofocus' => true, 'class' => 'input', 'placeholder' => 'Введите вашу фамилию', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>
    <?=
    $form->field($model, 'first_name')
        ->textInput(['class' => 'input', 'placeholder' => 'Введите ваше имя', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>
    <?=
    $form->field($model, 'middle_name')
        ->textInput(['class' => 'input', 'placeholder' => 'Введите ваше отчество', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'username')
        ->textInput(['class' => 'input', 'placeholder' => 'Введите логин', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>
    <?=
    $form->field($model, 'email')
        ->input('email', ['class' => 'input', 'placeholder' => 'Введите почту', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>
    <?=
    $form->field($model, 'password')
        ->passwordInput(['class' => 'input', 'placeholder' => 'Введите пароль', 'maxlength' => 256])
        ->label(false)
        ->error(false)
    ?>
    <?=
    $form->field($model, 'repeat_password')
        ->passwordInput(['class' => 'input', 'placeholder' => 'Повторите пароль', 'maxlength' => 256])
        ->label(false)
        ->error(false)
    ?>
    <?=
    $form->field($model, 'accept_personal')
        ->checkbox()
        ->error(false)
    ?>
    <?= $form->errorSummary($model, ['class' => 'error']); ?>

    <?= Html::submitButton('Зарегистрироваться') ?>

    <p class="no_ak">Уже есть аккаунт?</p>
    <?= Html::a('Авторизоваться', 'login', ['class' => 'butt']) ?>


    <?php ActiveForm::end(); ?>
</div>
