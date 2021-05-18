<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use app\assets\FormAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\UploadedFile;

$this->title = 'Редактирование заявки';
FormAsset::register($this);
?>
<div class="form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'title')
        ->textInput(['autofocus' => true, 'placeholder' => 'Введите название проблемы'])
        ->label(false)
        ->error(false)
    ?>
    <?=
    $form->field($model, 'description')
        ->textarea(['placeholder' => 'Введите описание проблемы'])
        ->label(false)
        ->error(false)
    ?>
    <?= $form->errorSummary($model, ['class' => 'error']); ?>
    <?= Html::submitButton('Создать') ?>

    <?php ActiveForm::end(); ?>

</div>