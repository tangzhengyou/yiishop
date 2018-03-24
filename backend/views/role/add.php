<?php
$form=\yii\widgets\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description')->textarea();

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\widgets\ActiveForm::end();
