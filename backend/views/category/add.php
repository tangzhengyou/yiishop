<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/18
 * Time: 18:40
 */
/** @var $this \yii\web\View */
$form = \yii\bootstrap\ActiveForm::begin();
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

\yii\bootstrap\ActiveForm::end();?>
<script>
    function onClick(e,treeId, treeNode) {
           $("#category-parent_id").val(treeNode .id)
           console.dir(treeNode .id);
//        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
//        zTree.expandNode(treeNode);
    }
</script>