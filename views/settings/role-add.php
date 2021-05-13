<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = $title;
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

    <?= Html::submitButton($button_text) ?>


    <?php ActiveForm::end(); ?>

</div>