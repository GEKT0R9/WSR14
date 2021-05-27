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

    <div class="user_info">
        <div class="user_img">
            <p><?= $first_letter ?></p>
        </div>

        <h3 class="fio"><?= $fio ?></h3>
        <?= Html::a(
            'Редактировать',
            ['settings/user-edit', 'id' => Yii::$app->user->id],
            ['class' => 'edit']
        ) ?>
        <p class="mail"><?= $email ?></p>

        <div class="exit">

            <?= Html::beginForm(['/main/logout'], 'post') ?>
            <?= Html::submitButton('выйти') ?>
            <?= Html::endForm() ?>
        </div>
    </div>
    <?= Html::a('Создать заявку', '/profile/create-request', ['class' => 'create']) ?>

    <div class="problems">
        <?php $form = ActiveForm::begin(['id' => 'sub_form']); ?>
        <?= $form->field($model, 'status_filt')->dropDownList($status, ['class' => 'filt'])->label(false) ?>
        <?= $form->field($model, 'criteria_filt')->dropDownList($criteria, ['class' => 'filt'])->label(false) ?>
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
                                <label data-toggle="modal" data-target="#accept_modal" >Принять</label>
                                <? if ($value['type_id'] == 1): ?>
                                    <label><?= Html::a('Редактировать', ['edit-request', 'id' => $value['id']]) ?></label>
                                <? else: ?>
                                    <label data-toggle="modal" data-target="#reject_modal">Отменить</label>
                                <? endif; ?>
                            </div>
                        <? endif; ?>
                        <? if ($value['allow_del']): ?>
                            <label class="del_but" data-toggle="modal" data-target="#del_modal">Удалить заявку</label>
                        <? endif; ?>
                        <? if (!empty($value['comments'])): ?>
                        <? foreach ($value['comments'] as $item): ?>
                            <div class="comment">
                                <h4><?= $item['role'] ?></h4>
                                <h6><?= $item['date'] ?></h6>
                                <p><?= $item['text'] ?></p>
                            </div>
                        <? endforeach; ?>
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


    <div class="info_menu">

    </div>

    <? $modal = Modal::begin([
        'id' => 'del_modal',
        'header' => '<h2>Вы действительно хотите удалить заявку?</h2>',
    ]); ?>
    <div class="del_window">
        <a class="yes"><input type="hidden" value="">Удалить</a>
        <label class="no" data-dismiss="modal">Отмена</label>
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
        <a id="accept_yes" class="yes"><input type="hidden" value="">Принять</a>
        <label for="accept" class="no" data-dismiss="modal">Отмена</label>
    </div>
    <?php $modal->end(); ?>

    <? $modal = Modal::begin([
        'id' => 'reject_modal',
        'header' => '<h2>Отменить заявку?</h2>',
    ]); ?>
    <div class="reject_window">
        <h2>Отменить заявку?</h2>
        <a id="reject_yes" class="yes"><input type="hidden" value="">Отменить</a>
        <label for="reject" class="no" data-dismiss="modal">Отмена</label>
    </div>
    <?php $modal->end(); ?>
</div>