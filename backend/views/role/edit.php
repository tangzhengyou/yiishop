<?php
$form=\yii\widgets\ActiveForm::begin();
echo $form->field($model,'name')->textInput(['disabled'=>'disabled']);
echo $form->field($model,'description')->textarea();
echo $form->field($model,'permissions')->checkboxList($persArr);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\widgets\ActiveForm::end();
