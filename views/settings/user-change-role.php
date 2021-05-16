<?php

use app\assets\FormAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Смена роли';
FormAsset::register($this);
?>
<div class="form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'role')
        ->dropDownList($roles)
        ->label(false)
        ->error(false)
    ?>

    <?= $form->errorSummary($model, ['class' => 'error']); ?>
    <?= Html::submitButton('Сохранить') ?>

    <?php ActiveForm::end(); ?>

</div>