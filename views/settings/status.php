<?php

use app\assets\DirAsset;
use yii\grid\GridView;
use yii\helpers\Html;
$this->title = 'Настройки';
DirAsset::register($this);

?>

<? if(true): ?>
    <?= Html::a('Создать статус', 'status-add', ['class' => 'butt']) ?>
<?endif;?>
<div class="table">
    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => $columns,
        'summary' => "",
    ]) ?>
</div>