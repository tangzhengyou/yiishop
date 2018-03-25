<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Goods */
/* @var $form ActiveForm */
?>
<div class="goods-add">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'sort') ?>
        <?= $form->field($model, 'brand_id')->dropDownList($brandsArr,['prompt'=>'请选择商品品牌'])?>
        <?= $form->field($model, 'logo')->widget(\manks\FileInput::className(),[]) ?>
        <?= $form->field($model, 'images')->widget(\manks\FileInput::className(),[
            'clientOptions' => [
                'pick' => [
                    'multiple' => true,
                ],
                // 'server' => Url::to('upload/u2'),
                // 'accept' => [
                // 	'extensions' => 'png',
                // ],
            ],
            ]); ?>
        <?= $form->field($intro,'content')->widget(\kucha\ueditor\UEditor::className(),[])?>
        <?= $form->field($model, 'category_id')->dropDownList($catesArr,['prompt'=>'请选择商品类别'])?>
        <?= $form->field($model, 'market_price') ?>
        <?= $form->field($model, 'shop_price') ?>
        <?= $form->field($model, 'status')->radioList(['下架','上架'],['value'=>1])?>
        <?= $form->field($model, 'stock') ?>
        <?= $form->field($model, 'sn') ?>


        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- goods-add -->
