<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/18
 * Time: 18:40
 */
/** @var $this \yii\web\View */
$form = \yii\bootstrap\ActiveForm::begin();
echo \yii\bootstrap\Html::a('返回首页',['index'],['class'=>'btn btn-primary glyphicon glyphicon-share-alt']);
echo $form->field($cate,'name');
echo $form->field($cate,'intro')->textarea();
echo $form->field($cate,'parent_id')->textInput(['value'=>0]);
echo \liyuze\ztree\ZTree::widget([
    'setting' => '{
			data: {
				simpleData: {
					enable: true,
					pIdKey:"parent_id",
				}	
			},
			callback: {
				onClick: onClick
			}
		}',
    'nodes' =>$catesJson,
]);

echo \yii\bootstrap\Html::submitButton("提交",['class'=>'btn btn-info']);


\yii\bootstrap\ActiveForm::end();
//ztree展开
//定义JS代码块
$js = <<<JS
//得到Ztree对象
   var treeObj = $.fn.zTree.getZTreeObj("w1");
//得到当前节点对象
   var node = treeObj.getNodeByParam("id", "$cate->parent_id", null);
   //选中当前节点
   treeObj.selectNode(node);
   //设置parent_id的值
   $("#category-parent_id").val($cate->parent_id);
//调用展开方法
    treeObj.expandAll(true);
JS;
//注册JS 把JS代码追加到JQuery之后
$this->registerJs($js);
//ztree展开结束
?>

<script>
    function onClick(e,treeId, treeNode) {
           $("#category-parent_id").val(treeNode .id)
           console.dir(treeNode .id);
//        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
//        zTree.expandNode(treeNode);
    }
</script>