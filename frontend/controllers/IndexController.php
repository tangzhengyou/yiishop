<?php

namespace frontend\controllers;

use backend\models\Category;
use backend\models\Goods;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {

        return $this->render('index');
    }

    /**分类列表
     * @param $id分类id
     * @return string
     */
    public function actionList($id){
        //通过分类id的打斗当前分类对象
        $cate = Category::findOne($id);
        //通过分类id得到所有子分类
        $cateSon = Category::find()->where(['tree'=>$cate->tree])->andWhere(['>=','lft',$cate->lft])->andWhere(['<=','rgt',$cate->rgt])->all();
        //通过当二维数组提取成一维数组
        $cateId = array_column($cateSon,'id');
        //var_dump($cateId);exit;
        //得到当前分类的所有商品
        $goods = Goods::find()->where(['in','category_id',$cateId])->andWhere(['status'=>1])->orderBy('sort')->all();





        //var_dump($goods);exit;


        return $this->render('list',compact('goods'));
    }

}
