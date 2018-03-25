<?php

namespace backend\controllers;

use backend\Filters\RbacFilter;
use backend\models\Brand;
use backend\models\Category;
use backend\models\Goods;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class GoodsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
          'rbac'=>[
              'class'=>RbacFilter::className(),
          ]
        ];
    }
//
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    public function actionIndex()
    {
        //获取所有数据
        $query = Goods::find();
        //
        $minPrice =\Yii::$app->request->get('minPrice');
        $maxPrice =\Yii::$app->request->get('maxPrice');
        $keyword =\Yii::$app->request->get('keyword');
        $status=\Yii::$app->request->get('status');
        if($minPrice){
            $query->andWhere("shop_price>={$minPrice}");
        }
        if($maxPrice){
            $query->andWhere("shop_price<={$maxPrice}");
        }
        if($keyword !==""){
            $query->andWhere("name like '%{$keyword}%' or sn like '%{$keyword}%'");
        }
        if($status==="0" or $status==="1"){
            $query->andWhere(['status'=>$status]);
        }


        //数据的总条数 每页显示多少条 当前页
        $count = $query->count();
        //创建一个分页对象

        $page = new Pagination([
            //注：pagesize必须小于总数据条数
            'pageSize' => 4,
            'totalCount' => $count,
        ]);
        $goods = $query->offset($page->offset)->limit($page->limit)->all();

        return $this->render('index', compact('goods', 'page'));
    }

    public function actionAdd()
    {
        //创建商品模型对象
        $model = new Goods();
        //创建详情表模型对象
        $intro = new GoodsIntro();

        $brands = Brand::find()->all();
        $brandsArr= ArrayHelper::map($brands,'id','name');
        $cates = Category::find()->all();
        $catesArr= ArrayHelper::map($cates,'id','name');

        //判断是否post方式提交
        if (\Yii::$app->request->isPost) {
            //数据绑定goods
            $model->load(\Yii::$app->request->post());

            //数据绑定intro
            $intro->load(\Yii::$app->request->post());
            //后台验证
            if ($model->validate() && $intro->validate() ) {

                 //var_dump($model->images);exit();

                //判断货号（sn）是否有值
                if (!$model->sn) {
                    $dayTime =strtotime( date('Ymd'));

                     //找出当前商品数量
                    $count = Goods::find()->where(['>','create_time',$dayTime])->count();
                    $count +=1;
                    $countStr="0000".$count;
                    $countStr=substr($countStr,-5);


                    $model->sn=date('Ymd').$countStr;

                }else{
                    //TODO

                }
                //保存数据
                if ($model->save()) {
                    //操作商品内容
                    $intro->goods_id = $model->id;
                    $intro->save();

                    //多图操作 循环images
                    foreach ($model->images as $image) {

                        $gallery =new GoodsGallery();
                        //赋值
                        $gallery->goods_id=$model->id;
                        $gallery->path=$image;
                        //保存图片
                        $gallery->save();
                        //添加成功提示
                        \Yii::$app->session->setFlash('success',"商品添加成功");
                        return $this->redirect(['index']);


                    }

                    //添加成功提示
                    \Yii::$app->session->setFlash('success', "添加成功");
                    //添加成功跳转
                    return $this->redirect(['index']);
                }

            } else {
                //打印错误信息
             var_dump($model->errors);
             var_dump($intro->errors);exit;
            }
        }

        return $this->render('add', compact('model','brandsArr','catesArr','intro'));


    }
    public function actionEdit($id)
    {
        //创建商品模型对象
        $model = Goods::findOne($id);
        //创建详情表模型对象
        $intro = GoodsIntro::findOne(['goods_id'=>$id]);

        $brands = Brand::find()->all();
        $brandsArr = ArrayHelper::map($brands,'id','name');
        $cates = Category::find()->all();
        $catesArr= ArrayHelper::map($cates,'id','name');

        //判断是否post方式提交
        if (\Yii::$app->request->isPost) {
            //数据绑定goods
            $model->load(\Yii::$app->request->post());

            //数据绑定intro
            $intro->load(\Yii::$app->request->post());
            //后台验证
            if ($model->validate() && $intro->validate() ) {

                 //var_dump($model->images);exit();

                //判断货号（sn）是否有值
                if (!$model->sn) {
                    $dayTime =strtotime( date('Ymd'));

                     //找出当前商品数量
                    $count = Goods::find()->where(['>','create_time',$dayTime])->count();
                    $count +=1;
                    $countStr="0000".$count;
                    $countStr=substr($countStr,-5);


                    $model->sn=date('Ymd').$countStr;

                }else{
                    //TODO

                }
                //保存数据
                if ($model->save()) {
                    //操作商品内容
                    $intro->goods_id = $model->id;
                    $intro->save();

                    //多图操作
                    //编辑之前一定要删除当前商品所对应的所有图片
                    GoodsGallery::deleteAll(['goods_id'=>$id]);
                    // 循环images
                    foreach ($model->images as $image) {

                        $gallery =new GoodsGallery();
                        //赋值
                        $gallery->goods_id=$model->id;
                        $gallery->path=$image;
                        //保存图片
                        $gallery->save();
                        //添加成功提示
                        \Yii::$app->session->setFlash('success',"商品添加成功");
                        return $this->redirect(['index']);


                    }

                    //添加成功提示
                    \Yii::$app->session->setFlash('success', "添加成功");
                    //添加成功跳转
                    return $this->redirect(['index']);
                }

            } else {
                //打印错误信息
             var_dump($model->errors);
             var_dump($intro->errors);exit;
            }
        }
        //从数据库中找出当前商品对应的所有图片
        $images = GoodsGallery::find()->where(['goods_id'=>$id])->asArray()->all();
         var_dump($images);exit;
        //把二维数组转成指定的一维数组
        $images = array_column($images,'path');
        $model->images = $images;
        return $this->render('add', compact('model','brandsArr','catesArr','intro'));


    }
    public function actionDel($id){
        if (Goods::findOne($id)->delete() && GoodsIntro::findOne(['goods_id'=>$id])->delete() && GoodsGallery::findOne(['goods_id'=>$id])->delete()){
            return $this->redirect(['index']);
        }
    }

    /**判断状态
     * @param $id
     * @return \yii\web\Response
     */
    public function actionStatus($id){
        //找到对应状态的id
        $model = Goods::findOne($id);
        //判断
        if ($model->status == 1){
            $model->status=0;
            $model->save();
            return $this->redirect(['index']);
        }else{
            $model->status=1;
            $model->save();
            return $this->redirect(['index']);
        }
    }
}
