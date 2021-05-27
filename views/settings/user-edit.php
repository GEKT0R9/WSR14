<?php

use app\assets\FormAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Редактирование пользователя';
FormAsset::register($this);
?>
<div class="form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false)->error(false) ?>

    <?=
    $form->field($model, 'last_name')
        ->textInput(['placeholder' => 'Введите фамилию', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'first_name')
        ->textInput(['placeholder' => 'Введите имя', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'middle_name')
        ->textInput(['placeholder' => 'Введите отчество', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'username')
        ->textInput(['placeholder' => 'Введите логин', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'email')
        ->textInput(['placeholder' => 'Введите электронную почту', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'edit_password')
        ->checkbox()
        ->error(false)
    ?>

    <?=
    $form->field($model, 'password')
        ->textInput(['placeholder' => 'Введите новый пароль', 'maxlength' => 256])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'repeat_password')
        ->textInput(['placeholder' => 'Повторите пароль', 'maxlength' => 256])
        ->label(false)
        ->error(false)
    ?>
    <?= $form->errorSummary($model, ['class' => 'error']); ?>
    <?= Html::submitButton('Сохранить') ?>

    <?php ActiveForm::end(); ?>

</div>