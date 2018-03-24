<?php
/* @var $this yii\web\View */
?>
<h1>角色列表</h1>


<p>
    <?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info glyphicon glyphicon-plus'])?>
<table class="table">
    <tr>

        <th>name</th>
        <th>description</th>
        <th>permission</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role):?>
        <tr>
            <td><?=strpos($role->name,'/')!==false?"---":""?><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td>
                <?php
                //得到当前角色所对应的所有权限
                $auth = Yii::$app->authManager;
                $pers=$auth->getPermissionsByRole($role->name);
                $html="";
                foreach ($pers as $per){
                    echo $per->description.",";
                }

                $html = trim($html,',');
                echo $html;

                ?>



            </td>
            <td>
                <?=\yii\bootstrap\Html::a("",['edit','name'=>$role->name],['class'=>'btn btn-success glyphicon glyphicon-pencil'])?>
                <?=\yii\bootstrap\Html::a("",['del','name'=>$role->name],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>

            </td>

        </tr>
    <?php endforeach;?>

</table>

</p>

