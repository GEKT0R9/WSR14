<?php

use app\assets\DirAsset;

use yii\grid\GridView;
use yii\helpers\Html;
$this->title = 'Роли';
DirAsset::register($this);
?>
<div>
    <?= Html::a('Создать роль', 'role-add', ['class' => 'butt']) ?>
</div>
<div class="table">
    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => $columns,
        'summary' => "",
    ]) ?>
</div>