<?php
/* @var $this yii\web\View */
?>
<h3>品牌列表</h3>

<p>
    <?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info glyphicon glyphicon-plus'])?>
<table class="table">
    <tr>
        <th>id</th>
        <th>name</th>
        <th>logo</th>
        <th>sort</th>
        <th>status</th>
        <th>intro</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand):?>
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?php
            $imgPath=strpos($brand->logo,"ttp://")?$brand->logo:"/".$brand->logo;
            echo \yii\bootstrap\Html::img($imgPath,['height'=>40]);


            ?></td>
        <td><?=$brand->sort?></td>
        <td><?=$brand->status?></td>
        <td><?=$brand->intro?></td>
        <td>
            <?=\yii\bootstrap\Html::a("编辑",['edit','id'=>$brand->id],['class'=>'btn btn-success glyphicon glyphicon-pencil'])?>
            <?=\yii\bootstrap\Html::a("删除",['del','id'=>$brand->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>

        </td>

    </tr>
    <?php endforeach;?>
    
</table>

</p>
<?=\yii\widgets\LinkPager::widget([
        'pagination' => $page,
])?>