<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Редактирование пользователя';
?>
<div class="form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false)->error(false) ?>

    <?=
    $form->field($model, 'last_name')
        ->textInput(['placeholder' => 'Введите название проблемы'])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'first_name')
        ->textInput(['placeholder' => 'Введите название проблемы'])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'middle_name')
        ->textInput(['placeholder' => 'Введите название проблемы'])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'username')
        ->textInput(['placeholder' => 'Введите название проблемы'])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'email')
        ->textInput(['placeholder' => 'Введите название проблемы'])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'edit_password')
        ->checkbox(['placeholder' => 'Введите название проблемы'])
        ->error(false)
    ?>

    <?=
    $form->field($model, 'password')
        ->textInput(['placeholder' => 'Введите название проблемы'])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'repeat_password')
        ->textInput(['placeholder' => 'Введите название проблемы'])
        ->label(false)
        ->error(false)
    ?>
    <?= $form->errorSummary($model, ['class' => 'error']); ?>
    <?= Html::submitButton('Сохранить') ?>

    <?php ActiveForm::end(); ?>

</div>