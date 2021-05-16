<?php
use yii\helpers\Html;
$this->title = 'Настройки';
?>
<div>
    <?= Html::a('Бизнесс процесс', 'settings/status') ?>
    <?= Html::a('Роли', 'settings/roles') ?>
    <?= Html::a('Пользователи', 'settings/users') ?>
</div>