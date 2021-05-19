<?php

use app\assets\FormAsset;
use app\assets\RoleToAccessAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = $title;
RoleToAccessAsset::register($this);
FormAsset::register($this);
?>
<div class="content">
    <div class="access_form form">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin(); ?>
        <div style="display: grid; grid-template-columns: 3fr 1fr 3fr;">
            <div>
                <?=
                $form->field($model, 'all_access_list')
                    ->listBox($all_list, ['id' => 'access_all_list', 'multiple' => 'true'])
                ?>
                <a id="go_to_role">Переместить</a>
            </div>
            <div class="access_info_all">
                <? foreach ($access_info as $item): ?>
                    <div id="access_<?= $item['id'] ?>" class="hidden access_info">
                        <h2><?= $item['title'] ?></h2>
                        <h3><?= $item['description'] ?></h3>
                    </div>
                <? endforeach; ?>
            </div>
            <div>
                <?=
                $form->field($model, 'role_access_list')
                    ->listBox($role_list, ['id' => 'access_role_list', 'multiple' => 'true']);
                ?>
                <a id="go_to_list">Переместить</a>
            </div>
        </div>
        <?= Html::submitButton($button_text) ?>
        <?php ActiveForm::end(); ?>

    </div>
</div>