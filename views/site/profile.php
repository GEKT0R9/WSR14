<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use app\assets\ProfileAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Profile';
ProfileAsset::register($this);
?>

<div class="content">
    <input type="checkbox" id="info_menu">

    <div class="user_info">
        <div class="user_img">
            <p><?= $first_letter ?></p>
        </div>
        <h3 class="fio"><?= $fio ?></h3>
        <p class="mail"><?= $email ?></p>
        <div class="exit">
            <?= Html::beginForm(['/site/logout'], 'post') ?>
            <?= Html::submitButton('выйти') ?>
            <?= Html::endForm() ?>
        </div>
    </div>
    <?= Html::a('Создать заявку', 'create-request', ['class' => 'create']) ?>
    <?php $form = ActiveForm::begin(['id' => 'sub_form']); ?>
    <?= $form->field($model, 'filt')->dropDownList($status,['id' => 'filt']) ?>
    <?php ActiveForm::end(); ?>
    <div class="problems">
        <? foreach ($requests as $key => $value) : ?>
            <label class="problem" for="info_menu" id="problem_<?= $value['id'] ?>">
                <img class="hidden" src="data:image/png;base64,<?= $value['img'] ?>">
                <h2 class="title"><?= $value['title'] ?></h2>
                <p class="description"><?= $value['description'] ?></p>
                <h3 class="status"><?= $value['status'] ?></h3>
                <p class="category"><?= $value['criterion'] ?></p>
                <p class="date"><?= $value['date'] ?></p>
            </label>
        <? endforeach; ?>
        <?= LinkPager::widget([
            'pagination' => $pages,
        ]); ?>
    </div>
    <label class="bg" for="info_menu"></label>
    <input type="checkbox" id="del">
    <div class="info_menu">
        <div class="img"></div>
        <h2 class="title_menu"></h2>
        <p class="description_menu"></p>
        <h3 class="status_menu">Статус</h3>
        <div class="inform">
            <p class="category_menu">Категория</p>
            <p class="date_menu"></p>
        </div>
        <div class="inform">

            <p class="date_menu"></p>
        </div>
        <label for="del" class="del_but">Удалить заявку</label>
    </div>
    <label class="bg2" for="del"></label>
    <div class="del_window">
        <h2>Вы действительно хотите удалить заявку?</h2>
        <a class="yes"><input type="hidden" value="">Удалить</a>
        <label for="del" class="no">Отмена</label>
    </div>
</div>

<!--<div class="site-login">-->
<!--    <h1>--><? //= $fio ?><!--</h1>-->
<!--    <p>--><? //= $email ?><!--</p>-->
<!---->
<!--    --><? //= Html::a('Создать заявку', 'create-request', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
<!---->
<!--    <div>-->
<!--        --><? //= Html::beginForm(['/site/logout'], 'post') ?>
<!--        --><? //= Html::submitButton('Выйти', ['class' => 'btn btn-primary']) ?>
<!--        --><? //= Html::endForm() ?>
<!--    </div>-->
<!---->
<!--    --><? // foreach ($requests as $key => $value) : ?>
<!--        <div>-->
<!--            <div id="title">-->
<!--                --><? //= $value['title'] ?>
<!--            </div>-->
<!--            <div>-->
<!--                --><? //= $value['description'] ?>
<!--            </div>-->
<!--            <div>-->
<!--                --><? //= $value['date'] ?>
<!--            </div>-->
<!--            <div>-->
<!--                <img src="data:image/png;base64,--><? //= $value['img'] ?><!--">-->
<!--            </div>-->
<!--            <div>-->
<!--                <button class="del" value="--><? //= $value['id'] ?><!--">Удалить</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    --><? // endforeach; ?>
<!--</div>-->