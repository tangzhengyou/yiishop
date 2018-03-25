<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $form ActiveForm */
?>
<div class="admin-add">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($admin, 'username') ?>
        <?= $form->field($admin, 'password_hash') ?>
        <?= $form->field($admin, 'status')->radioList(['禁用',' 激活'],['value'=>1]) ?>

        <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- admin-add -->
