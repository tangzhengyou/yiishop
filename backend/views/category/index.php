<?php
/* @var $this yii\web\View */
?>

<h3>商品类别列表</h3>

<p>
    <?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info glyphicon glyphicon-plus'])?>
    <?= leandrogehlen\treegrid\TreeGrid::widget([
        'dataProvider' => $dataProvider,
        'keyColumnName' => 'id',
        'parentColumnName' => 'parent_id',
        'parentRootValue' => '0', //first parentId value
        'pluginOptions' => [
            'initialState' => 'collapsed',
        ],
        'columns' => [
            'name',
            'id',
            'parent_id',


            ['class' => \backend\components\ActionColumn::className()]
        ]
    ]); ?>

</table>
</p>
