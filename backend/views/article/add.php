<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $form ActiveForm */
?>
<div class="article-add">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'cate_id')->dropDownList([$catesArr])?>
        <?= $form->field($model, 'status')->radioList(['0'=>"禁用",'1'=>"激活"])?>

        <?= $form->field($model, 'sort')->textInput(['value'=>100]) ?>
        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'intro')->textarea() ?>
        <?=$form->field($content,'detail')->widget('kucha\ueditor\UEditor',[]);?>



    <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- article-add -->
