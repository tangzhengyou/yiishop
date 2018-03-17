<?php
/* @var $this yii\web\View */
?>
<h3>文章列表</h3>

<p>
    <?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-info glyphicon glyphicon-plus'])?>
<table class="table">
    <tr>
        <th>id</th>
        <th>cate_id</th>
        <th>title</th>
        <th>intro</th>
        <th>status</th>
        <th>sort</th>
        <th>create_time</th>

        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->cate->name?></td>
            <td><?=$article->title?></td>
            <td><?=$article->intro?></td>
            <td> <?php
                if ($article->status){
                    echo \yii\bootstrap\Html::a("",['status','id'=>$article->id],['class'=>'glyphicon glyphicon-ok']);

                }else{
                    echo \yii\bootstrap\Html::a("",['status','id'=>$article->id],['class'=>'glyphicon glyphicon-remove']);
                }

                ?></td>

            <td><?=$article->sort?></td>
            <td><?=date("Ymd H:i:s",$article->create_time)?></td>

            <td>

                <?=\yii\bootstrap\Html::a("编辑",['edit','id'=>$article->id],['class'=>'btn btn-success glyphicon glyphicon-pencil'])?>
                <?=\yii\bootstrap\Html::a("删除",['del','id'=>$article->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>

            </td>

        </tr>
    <?php endforeach;?>

</table>

</p>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $page,
])?>
