<?php

use app\assets\RoleToAccessAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = $title;
RoleToAccessAsset::register($this);
?>
<div class="content">
    <div class="form">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin(); ?>
        <div style="display: grid; grid-template-columns: 2fr 1fr 2fr;">
            <div>
                <?=
                $form->field($model, 'all_access_list')
                    ->listBox($all_list, ['id' => 'access_all_list', 'multiple' => 'true'])
                ?>
                <a id="go_to_role">Переместить</a>
            </div>
            <div>

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