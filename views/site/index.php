<?php

/* @var $this yii\web\View */

use app\assets\IndexAsset;

$this->title = 'My Yii Application';
IndexAsset::register($this);
?>
<div class="content">
    <div class="problems">
        <div class="counter">
            <h3>Проблем решено:</h3>
            <p id="counter"><?=$count?></p>
        </div>
        <?foreach ($requests as $key => $value):?>
            <div class="promlem">
                <div class="before img">
                    <img src="data:image/png;base64,<?= $value['before_img'] ?>">
                </div>
                <div class="after img">
                    <img src="data:image/png;base64,<?= $value['after_img'] ?>">
                </div>
                <h2 class="title"><?=$value['title']?></h2>
                <p class="description"><?=$value['description']?></p>
                <div class="inform">
                    <p class="category"><?=$value['criterion']?></p>
                    <p class="date"><?=$value['date']?></p>
                </div>
            </div>
        <?endforeach;?>
</div>

