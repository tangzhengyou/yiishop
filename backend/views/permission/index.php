<?php
/* @var $this yii\web\View */
?>
<h1>权限列表</h1>


<p>
    <?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info glyphicon glyphicon-plus'])?>
<table class="table">
    <tr>

        <th>name</th>
        <th>description</th>

        <th>操作</th>
    </tr>
    <?php foreach ($pers as $per):?>
        <tr>
            <td><?=strpos($per->name,'/')!==false?"---":""?><?=$per->name?></td>
            <td><?=$per->description?></td>

            <td>
                <?=\yii\bootstrap\Html::a("",['edit','name'=>$per->name],['class'=>'btn btn-success glyphicon glyphicon-pencil'])?>
                <?=\yii\bootstrap\Html::a("",['del','name'=>$per->name],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>

            </td>

        </tr>
    <?php endforeach;?>

</table>

</p>

