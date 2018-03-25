<?php
/* @var $this yii\web\View */
?>
<h1>管理员列表</h1>

<p>
    <?=\yii\bootstrap\Html::a('',['add'],['class'=>'btn btn-info glyphicon glyphicon-plus'])?>
<table class="table">
    <tr>
        <th>id</th>


        <th>username</th>
        <th>auth_key</th>

        <th>status</th>
        <th>created_at</th>
        <th>updated_at</th>
        <th>login_at</th>
        <th>login_ip</th>



        <th>操作</th>
    </tr>
    <?php foreach ($admins as $admin):?>
        <tr>
            <td><?=$admin->id?></td>

            <td><?=$admin->username?></td>
            <td><?=$admin->auth_key?></td>
            <td><?=$admin->status?></td>

            <td><?=date("Ymd H:i:s",$admin->created_at)?></td>
            <td><?=date("Ymd H:i:s",$admin->updated_at)?></td>
            <td><?=date("Ymd H:i:s",$admin->login_at)?></td>
            <td><?=$admin->login_ip?></td>

            <td>
                <?=\yii\bootstrap\Html::a("",['edit','id'=>$admin->id],['class'=>'btn btn-success glyphicon glyphicon-pencil'])?>
                <?=\yii\bootstrap\Html::a("",['del','id'=>$admin->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>

            </td>

        </tr>
    <?php endforeach;?>

</table>

</p>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $page,
])?>
