<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use app\assets\DirAsset;

$this->title = $title;

DirAsset::register($this);
?>
<? if($add_row_for): ?>
<?php $form = ActiveForm::begin(['id' => 'sub_form']); ?>
<?=
$form->field($model, 'text')
    ->textInput(['class' => 'input', 'placeholder' => 'Введите статус'])
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