<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use app\assets\DirAsset;

$this->title = $title;

DirAsset::register($this);
?>
<? if(
        $add_row_for
        && (Yii::$app->user->identity->isAvailable('dir_add_status')
            || Yii::$app->user->identity->isAvailable('dir_add_criteria'))): ?>
<?php $form = ActiveForm::begin(['id' => 'sub_form']); ?>
<?=
$form->field($model, 'text')
    ->textInput(['class' => 'input', 'placeholder' => 'Введите '.$button_text, 'maxlength' => 50])
    ->label(false)
    ->error(false)
?>
<?= $form->errorSummary($model, ['class' => 'error']); ?>
<?= Html::submitButton('Добавить', ['class' => 'butt']) ?>
<?php ActiveForm::end(); ?>
<?endif;?>
<div class="table">
    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => $columns,
        'summary' => "",
    ]) ?>
</div>