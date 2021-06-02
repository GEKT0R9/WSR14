<?php

/* @var $this yii\web\View */

use app\assets\AllRequestsAsset;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Все решённые заявки';
AllRequestsAsset::register($this);
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
    <div>
        <?= LinkPager::widget([
            'pagination' => $pages,
        ]); ?>
    </div>
</div>



