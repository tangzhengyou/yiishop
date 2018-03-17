<?php
/* @var $this yii\web\View */
?>
<h1>文章分类列表</h1>

<p>
    <?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info glyphicon glyphicon-plus'])?>
 <table class="table">
    <tr>
        <th>id</th>
        <th>name</th>
        <th>intro</th>
        <th>status</th>
        <th>sort</th>
        <th>is_help</th>
        <th>操作</th>

    </tr>
    <?php foreach ($cates as $cate):?>
    <tr>
        <td><?=$cate->id?></td>
        <td><?=$cate->name?></td>
        <td><?=$cate->intro?></td>
        <td><?=$cate->status?></td>
        <td><?=$cate->sort?></td>
        <td><?=$cate->is_help?></td>
        <td>
            <?=\yii\bootstrap\Html::a("编辑",['edit','id'=>$cate->id],['class'=>'btn btn-success glyphicon glyphicon-pencil '])?>
            <?=\yii\bootstrap\Html::a("删除",['del','id'=>$cate->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>


        </td>


    </tr>
<?php endforeach;?>


</table>
</p>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $page,
])?>
