<?php

use app\assets\FormAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = $title;
FormAsset::register($this);
?>
<div class="form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'title')
        ->textInput(['autofocus' => true, 'placeholder' => 'Введите название роли', 'maxlength' => 50])
        ->label(false)
        ->error(false)
    ?>

    <?=
    $form->field($model, 'description')
        ->textarea(['placeholder' => 'Введите описание роли', 'maxlength' => 100])
        ->label(false)
        ->error(false)
    ?>

    <?= Html::submitButton($button_text) ?>


    <?php ActiveForm::end(); ?>

</div>