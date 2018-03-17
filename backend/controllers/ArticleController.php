<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleContent;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取所有数据
        $query = Article::find();
        //数据的总条数 每页显示多少条 当前页
        $count = $query->count();
        //创建一个分页对象
        $page = new Pagination([
            //注：pagesize必须小于总数据条数
            'pageSize' => 3,
            'totalCount' => $count,
        ]);
        $articles=$query->offset($page->offset)->limit($page->limit)->all();
        //引入视图
        return $this->render('index',compact('articles','page'));
    }

    /**添加方法
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        //创建文章模型对象
        $model= new Article();
        //创建文章内容模型对象
        $content = new ArticleContent();
        //获取分类数据
        $cates = ArticleCategory::find()->all();
        //把二维数组转一维
        $catesArr = ArrayHelper::map($cates,'id','name');
        //判定post提交
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //数据绑定
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {
                //保存数据
                if ($model->save()) {
                   //再保存数据内容
                    $content->load($request->post());
                    //文章后台验证
                    if($content->validate()){
                        //给文章id赋值
                        $content->article_id=$model->id;
                        //保存文章内容
                        if ($content->save()) {
                            //成功提示
                            \Yii::$app->session->setFlash('success','添加成功');
                            //跳转主页面
                            return $this->redirect(['index']);
                        }
                    }
                }

            }else{
                //TODO
                var_dump($model->errors);exit;
            }

        }
//        var_dump($catesArr);exit;
        return $this->render('add',compact('model','content','catesArr'));
    }

    /**编辑方法
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){
        //创建文章模型对象
        $model=Article::findOne($id);
        //创建文章内容模型对象
        $content =ArticleContent::findOne($id);
        //获取分类数据
        $cates = ArticleCategory::find()->all();
        //把二维数组转一维
        $catesArr = ArrayHelper::map($cates,'id','name');
        //判定post提交
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //数据绑定
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {
                //保存数据
                if ($model->save()) {
                   //再保存数据内容
                    $content->load($request->post());
                    //文章后台验证
                    if($content->validate()){
                        //给文章id赋值
                        $content->article_id=$model->id;
                        //保存文章内容
                        if ($content->save()) {
                            //成功提示
                            \Yii::$app->session->setFlash('success','添加成功');
                            //跳转主页面
                            return $this->redirect(['index']);
                        }
                    }
                }

            }else{
                //TODO
                var_dump($model->errors);exit;
            }

        }
//        var_dump($catesArr);exit;
        return $this->render('add',compact('model','content','catesArr'));
    }

    /**删除方法
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDel($id){
        if (Article::findOne($id)->delete() && ArticleContent::findOne($id)->delete()) {
            return $this->redirect(['index']);
        }
    }

    public function actionStatus($id){
        //找到对应状态的id
        $model = Article::findOne($id);
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
