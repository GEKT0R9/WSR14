<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use app\assets\FormAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\UploadedFile;

$this->title = 'Создание заявки';
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
    <?=
    $form->field($model, 'criterion')
        ->dropDownList($criterion, ['class' => 'select'])
        ->label(false)
        ->error(false)
    ?>
    <div class="file_input">
        <div class="form-group">
            <label class="label">
                <span class="title">Добавить файл</span>
                <?=
                $form->field($model, 'image')
                    ->fileInput([
                        'class' => 'file',
                        'accept' => 'image/*',
                        'size' => '10MB',
                        'value' => UploadedFile::getInstance($model, 'image')
                    ])
                    ->label(false)
                    ->error(false)
                ?>
            </label>
        </div>
    </div>


    <?= Html::submitButton('Создать') ?>


    <?php ActiveForm::end(); ?>

</div>