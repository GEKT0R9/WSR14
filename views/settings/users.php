<?php

use app\assets\UsersAsset;
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Пользователи';
UsersAsset::register($this);
?>
<div class="tab">
    <? foreach ($users as $user): ?>
        <div class="plate">
            <h1>ФИО: <?= $user['fio'] ?></h1>
            <h3>Логин: <?= $user['username'] ?></h3>
            <h4>E-mail: <?= $user['email'] ?></h4>
            <h2>Роль: <?= $user['role'] ?></h2>
            <div>
                <?= Html::a('Редактировать', ['user-edit', 'id' => $user['id']]) ?>
                <?= Html::a('Изменить роль', ['user-change-role', 'id' => $user['id']]) ?>
            </div>
        </div>
    <? endforeach; ?>
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>