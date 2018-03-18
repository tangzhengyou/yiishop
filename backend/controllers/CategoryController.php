<?php

namespace backend\controllers;

use backend\models\Category;
use function Sodium\compare;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Json;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {

        $query = Category::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index',compact("dataProvider"));
    }

    public function actionAdd(){
        $cate = new Category();
        //查询所有分类
        $cates = Category::find()->asArray()->all();
        $cates[]=['id'=>0,'name'=>'一级分类','parent_id'=>0];
        //转json字符串
        $catesJson = Json::encode($cates);
//        $catesJson = Json::encode($cates);
//        var_dump($catesJson );exit();
        //post提交
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //绑定数据
            $cate->load($request->post());
            //后台验证
            if ($cate->validate()) {
                //如果parent_id=0,添加一级分类
                if ($cate->parent_id==0) {
                    //创建一个一级分类
                    $cate->makeRoot();
                    //添加成功提示
                    \Yii::$app->session->setFlash('success',"添加一级分类:".$cate->name."成功");
                    //刷新
                    return $this->refresh();

                }else{

                    //1.创建父级分类
                    $cateParent=Category::findOne($cate->parent_id);
                    //3.把新的分类加入父级分类中
                    $cate->prependTo($cateParent);
                    //添加成功提示
                    \Yii::$app->session->setFlash('success',"添加{$cateParent->name}分类:".$cate->name."成功");
                    //刷新
                    return $this->refresh();

                }



            }else{
                var_dump($cate->errors);exit();

            }
        }


        return $this->render('add',compact('cate','catesJson'));


    }
    public function actionEdit($id ,$parent_id){
        $cate = Category::findOne($id && $parent_id);
        //查询所有分类
        $cates = Category::find()->asArray()->all();
        $cates[]=['id'=>0,'name'=>'一级分类','parent_id'=>0];
        //转json字符串
        $catesJson = Json::encode($cates);
//        $catesJson = Json::encode($cates);
//        var_dump($catesJson );exit();
        //post提交s
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //绑定数据
            $cate->load($request->post());
            //后台验证
            if ($cate->validate()) {
                //如果parent_id=0,添加一级分类
                if ($cate->parent_id==0) {
                    //创建一个一级分类
                    $cate->makeRoot();
                    //添加成功提示
                    \Yii::$app->session->setFlash('success',"添加一级分类:".$cate->name."成功");
                    //刷新
                    return $this->refresh();

                }else{

                    //1.创建父级分类
                    $cateParent=Category::findOne($cate->parent_id);
                    //3.把新的分类加入父级分类中
                    $cate->prependTo($cateParent);
                    //添加成功提示
                    \Yii::$app->session->setFlash('success',"添加{$cateParent->name}分类:".$cate->name."成功");
                    //刷新
                    return $this->refresh();

                }



            }else{
                var_dump($cate->errors);exit();

            }
        }


        return $this->render('add',compact('cate','catesJson'));


    }


}
