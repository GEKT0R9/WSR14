<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Пользователи';
?>
<div>
    <? foreach ($users as $user): ?>
        <div>
            <h1><?= $user['fio'] ?></h1>
            <h3><?= $user['username'] ?></h3>
            <h4><?= $user['email'] ?></h4>
            <h2><?= $user['role'] ?></h2>
            <div>
                <?= Html::a('Редактировать', ['user-edit', 'id' => $user['id']]) ?>
                <?= Html::a('Изменить роль', ['/directory', 'type' => 'status']) ?>
            </div>
        </div>
    <? endforeach; ?>
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>