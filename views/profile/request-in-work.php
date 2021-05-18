<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use app\assets\ProfileAsset;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Профиль';
ProfileAsset::register($this);
?>

<div class="content">
    <input type="checkbox" id="info_menu">

    <div class="problems">
        <?php $form = ActiveForm::begin(['id' => 'sub_form']); ?>
        <?= $form->field($model, 'filt')->dropDownList($status, ['id' => 'filt'])->label(false) ?>
        <?php ActiveForm::end(); ?>
        <? foreach ($requests as $key => $value) : ?>
            <label class="problem" for="info_menu" id="problem_<?= $value['id'] ?>_<?= $value['type_id'] ?>">
                <h2 class="title"><?= $value['title'] ?></h2>
                <p class="description"><?= $value['description'] ?></p>
                <h3 class="status"><?= $value['status'] ?></h3>
                <p class="category"><?= $value['criterion'] ?></p>
                <p class="date"><?= $value['date'] ?></p>
                <div class="hidden">
                    <div class="info_menu">
                        <div class="images">
                            <div class="before img">
                                <img src="data:image/png;base64,<?= $value['before_img'] ?>">
                            </div>
                            <div class="after img">
                                <img src="data:image/png;base64,<?= !empty($value['after_img']) ? $value['after_img'] : $value['before_img'] ?>">
                            </div>
                        </div>
                        <h2 class="title_menu"><?= $value['title'] ?></h2>
                        <p class="description_menu"><?= $value['description'] ?></p>
                        <h3 class="status_menu">Статус: <?= $value['status'] ?></h3>
                        <div class="inform">
                            <p class="category_menu">Категория: <?= $value['criterion'] ?></p>
                            <p class="date_menu"><?= $value['date'] ?></p>
                        </div>
                        <? if ($value['allow']): ?>
                            <div class="status_but">
                                <label data-toggle="modal" data-target="#accept_modal">Принять</label>
                                <label data-toggle="modal" data-target="#reject_modal">Отменить</label>
                            </div>
                        <? endif; ?>
                        <? if ($value['allow_del']): ?>
                            <label class="del_but" data-toggle="modal" data-target="#del_modal">Удалить заявку</label>
                        <? endif; ?>
                    </div>
                </div>
            </label>
        <? endforeach; ?>
        <?= LinkPager::widget([
            'pagination' => $pages,
        ]); ?>
    </div>
    <label class="bg" for="info_menu"></label>
    <div class="info_menu"></div>
    <? $modal = Modal::begin([
        'id' => 'del_modal',
        'header' => '<h2>Вы действительно хотите удалить заявку?</h2>',
    ]); ?>
    <div class="del_window">
        <a class="yes"><input type="hidden" value="">Удалить</a>
        <button data-dismiss="modal">Отмена</button>
    </div>
    <?php $modal->end(); ?>

    <? $modal = Modal::begin([
        'id' => 'accept_modal',
        'header' => '<h2>Принять заявку?</h2>',
    ]); ?>
    <div class="accept_window">
        <?php $form = ActiveForm::begin(['id' => 'accept_form', 'action' => '/profile/accept-request']); ?>
        <?=
        $form->field($model_accept, 'image')->fileInput(
            [
                'class' => 'file_input',
                'accept' => 'image/*',
                'size' => '10MB',
            ]
        )->label(null, ['class' => 'file_label']) ?>
        <p id="file_name"></p>
        <?= $form->field($model_accept, 'id')->hiddenInput(['class' => 'hidden_id'])->label(false) ?>
        <?php ActiveForm::end(); ?>
        <input id="status_type" type="hidden" value="">
        <textarea id="comment_accept" placeholder="Введите примичание"></textarea>
        <a id="accept_yes" class="yes"><input type="hidden" value="">Принять</a>
        <label class="no" data-dismiss="modal">Отмена</label>
    </div>
    <?php $modal->end(); ?>

    <? $modal = Modal::begin([
        'id' => 'reject_modal',
        'header' => '<h2>Отменить заявку?</h2>',
    ]); ?>
    <div class="reject_window">
        <textarea id="comment_reject" placeholder="Введите примичание"></textarea>
        <a id="reject_yes" class="yes"><input type="hidden" value="">Отменить</a>
        <label class="no" data-dismiss="modal">Отмена</label>
    </div>
    <?php $modal->end(); ?>
</div>