<?php
/* @var $this yii\web\View */
?>
<h1>商品类别列表</h1>

<p>
    <?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info glyphicon glyphicon-plus'])?>
    <table>
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
            ['class' => 'yii\grid\ActionColumn']
        ]
    ]); ?>

</table>
</p>
