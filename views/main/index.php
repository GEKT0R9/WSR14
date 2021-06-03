<?php

/* @var $this yii\web\View */

use app\assets\IndexAsset;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Главная';
IndexAsset::register($this);
?>
<div class="content">
    <div class="problems">
        <? foreach ($requests as $key => $value): ?>
            <div class="promlem" data-toggle="modal" data-target="#<?= 'modal' . $value['id'] ?>">
                <div class="card_image">
                    <div class="before img">
                        <img src="<?= Url::to(['/directory/img', 'id' => $value['before_img']]) ?>">
                    </div>
                    <div class="after img">
                        <img src="<?= Url::to(['/directory/img', 'id' => !empty($value['after_img']) ? $value['after_img'] : $value['before_img']]) ?>">
                    </div>
                </div>

                <h2 class="title"><?= $value['title'] ?></h2>
                <p class="description"><?= $value['description'] ?></p>
                <div class="inform">
                    <p class="category"><?= $value['criterion'] ?></p>
                    <p class="date"><?= $value['date'] ?></p>
                </div>
            </div>
            <? $modal = Modal::begin([
                'id' => 'modal' . $value['id'],
                'header' => '<h2>' . $value['title'] . '</h2>',
            ]); ?>
            <div class="modal_images">
                <div class="modal_img before img">
                    <img src="<?= Url::to(['/directory/img', 'id' => $value['before_img']]) ?>">
                </div>
                <div class="modal_img after img">
                    <img src="<?= Url::to(['/directory/img', 'id' => !empty($value['after_img']) ? $value['after_img'] : $value['before_img']]) ?>">
                </div>
            </div>
            <p class="modal_description"><?= $value['description'] ?></p>
            <div class="inform">
                <p class="category"><?= $value['criterion'] ?></p>
                <p class="date"><?= $value['date'] ?></p>
            </div>
            <? foreach ($value['comments'] as $item): ?>
                <div class="comment">
                    <h4><?= $item['role'] ?></h4>
                    <h6><?= $item['date'] ?></h6>
                    <p><?= $item['text'] ?></p>
                </div>
            <? endforeach; ?>
            <?php $modal->end(); ?>
        <? endforeach; ?>
    </div>
    <div class="second_grid">
        <div class="counter">
            <h3>Проблем решено:</h3>
            <p id="counter"><?= $count ?></p>
        </div>
        <? if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAvailable('notice') && !empty($notice_count)): ?>
            <a class="notice_a" href="/profile/request-in-work">
                <div class="notice">
                    <h3>Нерешённых заявок:</h3>
                    <p><?= $notice_count ?></p>
                </div>
            </a>
        <? endif; ?>
    </div>
    <? if ($count > 4): ?>
        <?= Html::a('Все заявки', ['/main/all-requests'], ['class' => 'a_all_req']) ?>
    <? endif; ?>
</div>


