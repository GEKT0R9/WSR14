<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'About';
?>
<?php $form = ActiveForm::begin(['id' => 'sub_form']); ?>
<?= $form->field($model, 'text')->textInput() ?>
<?= Html::submitButton('Добавить') ?>
<?php ActiveForm::end(); ?>

<?= GridView::widget([
    'dataProvider' => $provider,
    'columns' => $columns,
    'summary'=> "",
]) ?>
