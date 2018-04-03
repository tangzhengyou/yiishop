<?php

namespace frontend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use frontend\components\ShopCart;
use frontend\models\Address;
use frontend\models\Cart;
use function Sodium\add;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**商品详情
     * @param $id商品id
     */
    public function actionDetail($id){
    //找到当前数据
    $good = Goods::findOne($id);

    //找到当前商品对应的所有图片

//var_dump($good->imgs);exit;
    return $this->render('detail',compact('good'));
}

    /**添加购物车
     * @param $id商品id
     * @param $num商品数量
     */

    public function actionAddCart($id,$amount){


        if(Goods::findOne($id)===null){
            return $this->redirect(['index/index']);
        }



        if(\Yii::$app->user->isGuest){
            (new ShopCart())->add($id,$amount)->save();


//            //未登录存cookie
//            //得到cookie对象
//            $getCookie = \Yii::$app->request->cookies;
//            //得到原来购物车的数据
//            $cart = $getCookie->getValue('cart',[]);
//            //判断当前添加的商品ID在购物车中是否已经存在
//            if (array_key_exists($id, $cart)) {
//                //已经存在 值+$amount
//                $cart[$id] += $amount;
//            } else {
//                //新增
//                $cart[$id] = (int)$amount;
//            }
//            //把$id当作键，把$amount当作值
//            //1、设置cookie对象
//            $setCookie = \Yii::$app->response->cookies;
//            //2、创建一个cookie对象
//            $cookie = new Cookie([
//                'name'=>'cart',
//                'value' =>$cart
//            ]);
//            //3、通过cookie对象添加一个cookie
//            $setCookie->add($cookie);
            return $this->redirect(['cart-list']);

        }else{
            //已登录存数据库
            //判断当前用户购物车有没有商品
            //当前用户
            $userId =\Yii::$app->user->id;

            $cart = Cart::findOne(['goods_id'=>$id,'user_id'=>$userId]);
            //判断
            if($cart){
                //有商品存在 执行 + 商品数量 $cart->num = $cart->num +$amount
                $cart->num += $amount;
                $cart->save();

            }else{
                //没有商品存在  就新增商品
                //创建对象
                $cart = new Cart();
                //赋值
                $cart->goods_id=$id;
                $cart->num=$amount;
                $cart->user_id=$userId;

                //保存
                //$cart->save();
            }
            //保存
            $cart->save();
            return $this->redirect('cart-list');

        }


        //var_dump($id,$amount);exit;
    }
    public function actionCartList(){
        //判定是否登录
        //未登录
        if (\Yii::$app->user->isGuest){
            //未登录从cookie中取数据
            $cart = (new ShopCart())->get();
            //取出$cart中的所有key值

            //var_dump($cart);
            $goodsIds = array_keys($cart);
            //取购物车的所有商品
            $goods = Goods::find()->where(['in','id',$goodsIds])->all();
             //
            //var_dump($goods);exit;

        }else{
          //已登录 数据库

          //$cart = \Yii::$app->request->cookies->getValue('cart',[]);
          //从cookie中取出购物车中的数据
           $cart=Cart::find()->where(['user_id'=>\Yii::$app->user->id])->all();
           //把二维数组转一维数组 array(3) { [9]=> int(20) [2]=> int(2) [8]=> int(4) }
           $cart=ArrayHelper::map($cart,'goods_id','num');
            //var_dump($cart);exit;
            //取出$cart中的所有key值

            //var_dump($cart);
            $goodsIds = array_keys($cart);
            //取购物车的所有商品
            $goods = Goods::find()->where(['in','id',$goodsIds])->all();


        }


        return $this->render('list',compact('goods','cart'));

    }

    /**购物车修改
     * @param $id
     * @param $amount
     */
    public function actionUpdateCart($id,$amount){
        if(\Yii::$app->user->isGuest){

            (new ShopCart())->update($id,$amount)->save();


        }else{
            $userId =\Yii::$app->user->id;
            $cart= Cart::findOne(['goods_id'=>$id,'user_id'=>$userId]);
            $cart->num = $amount;
            $cart->save();



        }


    }

    /**删除购物车
     * @param $id
     */
    public function actionDelCart($id){
        if(\Yii::$app->user->isGuest){
            (new ShopCart())->del($id)->save();
//            //1、从cookie取出购物车数据
//            $cart = \Yii::$app->request->cookies->getValue('cart',[]);
//            //2、删除对应的数据
//            unset($cart[$id]);
//            //3、把$cart存到cookie
//            //3.1、设置cookie对象
//            $setCookie = \Yii::$app->response->cookies;
//            //3.2、创建一个cookie对象
//            $cookie = new Cookie([
//                'name'=>'cart',
//                'value' =>$cart
//            ]);
//            //3.3、通过cookie对象添加一个cookie
//            $setCookie->add($cookie);
            return Json::encode([
                'status'=>1,
                'msg'=>'删除成功'
            ]);

        }else{
//            (new ShopCart())->del($id)->save();
        }


    }


}
