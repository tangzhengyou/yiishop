<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取所有数据
        $query = ArticleCategory::find();
        //数据的总条数 每页显示多少条 当前页
        $count = $query->count();
        //创建一个分页对象
        $page = new Pagination([
            //注：pagesize必须小于总数据条数
            'pageSize' => 2,
            'totalCount' => $count,
        ]);
        $cates=$query->offset($page->offset)->limit($page->limit)->all();

        return $this->render('index',compact('cates','page'));


    }

    /**添加方法
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $model = new ArticleCategory();
        //判断post提交
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //是post绑定数据
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {
                //验证成功保存数据
                if ($model->save()) {
                    //添加成功提示
                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(['index']);
                }
            }else{
                //TODO：去掉就可以显示错误提示

            }
        }

        //引入视图
        return $this->render('add',compact('model'));

    }

    /**编辑方法
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){
        $model =ArticleCategory::findOne($id);
        //判断post提交
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //绑定数据
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {
                //保存数据
                if ($model->save()) {
                    //添加成功提示
                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(['index']);
                }
            }else{
                //TODO
                var_dump($model->errors);exit;
            }
        }

        //引入视图
        return $this->render('add',compact('model'));

    }

    /**删除方法
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDel($id){
        if (ArticleCategory::findOne($id)->delete()) {
            return $this->redirect(['index']);
        }
   }

}
