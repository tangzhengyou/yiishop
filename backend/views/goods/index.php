<?php
/* @var $this yii\web\View */
?>
<h3>商品列表</h3>
<?=\yii\bootstrap\Html::a('',['add'],['class'=>'btn btn-info glyphicon glyphicon-plus'])?>

    <form class="form-inline pull-right">
        <select class="form-control" name="status">
            <option >请选择</option>
            <option value="0" <?=Yii::$app->request->get('status')==="0"?"selected":""?>>下架</option>
            <option value="1" <?=Yii::$app->request->get('status')==="1"?"selected":""?>>上架</option>

        </select>
        <div class="form-group">
            <input type="text" class="form-control" id="minPrice" placeholder="最低价位" name="minPrice" size="7">
        </div>
        <b>-</b>
        <div class="form-group">
            <input type="text" class="form-control" id="maxPrice" placeholder="最高价位" name="maxPrice" size="7">
        </div> <div class="form-group">

            <input type="text" class="form-control" id="keyword" placeholder="名称或号码" name="keyword" size="7">
        </div>
        <button type="submit" class="btn btn-info "><span class="glyphicon glyphicon-search"></span></button>
    </form>

<p>

<table class="table">
    <tr>
        <th>id</th>
        <th>name</th>
        <th>sort</th>
        <th>brand_id</th>
        <th>logo</th>
        <th>category_id</th>
        <th>market_price</th>
        <th>shop_price</th>
        <th>status</th>
        <th>stock</th>
        <th>sn</th>
        <th>create_time</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods as $good):?>
        <tr>
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sort?></td>
            <td><?=$good->brand_id?></td>
            <td><img src="/<?=$good->logo?>" height="40"></td>
            <td><?=$good->category_id?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td> <?php
                if ($good->status==1){
                    echo \yii\bootstrap\Html::a("",['status','id'=>$good->id],['class'=>'上架']);

                }else{
                    echo \yii\bootstrap\Html::a("",['status','id'=>$good->id],['class'=>'下架']);
                }

                ?></td>
            <td><?=$good->stock?></td>
            <td><?=$good->sn?></td>
            <td><?=date("Ymd H:i:s",$good->create_time)?></td>

            <td>
                <?=\yii\bootstrap\Html::a("",['edit','id'=>$good->id],['class'=>'btn btn-success glyphicon glyphicon-pencil'])?>
                <?=\yii\bootstrap\Html::a("",['del','id'=>$good->id],['class'=>'btn btn-danger glyphicon glyphicon-trash'])?>

            </td>

        </tr>
    <?php endforeach;?>

</table>


</p>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $page,
])?>