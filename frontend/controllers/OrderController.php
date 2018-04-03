<?php

namespace frontend\controllers;

use backend\models\Delivery;
use backend\models\Goods;
use backend\models\Order;
use backend\models\OrderDetail;
use backend\models\PayType;
use frontend\models\Address;
use frontend\models\Cart;
use function Sodium\compare;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {

        //判定用户是否登录
        if(\Yii::$app->user->isGuest){



            return $this->redirect(['user/login','url'=>'/order/index']);
        }

        //用户id
        $userId=\Yii::$app->user->id;

        //收货人地址
        $addresss = Address::find()->where(['user_id'=>$userId])->all();

        //配送方式
        $deliverys = Delivery::find()->all();
        //支付方式
        $payTypes = PayType::find()->all();

        //取出商品 放在订单里

        $cart=Cart::find()->where(['user_id'=>\Yii::$app->user->id])->asArray()->all();
        //var_dump($cart);exit;
        //把二维数组转一维数组 array(3) { [9]=> int(20) [2]=> int(2) [8]=> int(4) }

        $cart=ArrayHelper::map($cart,'goods_id','num');

        //取出$cart中的所有key值

        //var_dump($cart);
        $goodsIds = array_keys($cart);
        //取购物车的所有商品
        $goods = Goods::find()->where(['in','id',$goodsIds])->all();

        //商品总价
        $shopPrice = 0;
        //商品总数量
        $shopNum = 0;
        foreach ($goods as $good){
            $shopPrice += $good->shop_price*$cart[$good->id];
            $shopNum += $cart[$good->id];
        }
            //$shopPrice = number_format($shopPrice,2);

        $request = \Yii::$app->request;
        //判定是否post
        if($request->isPost){

            $db = \Yii::$app->db;
            //开启事务
            $transaction = $db->beginTransaction();

            try {
                //创建订单对象
                $order = new Order();

                //var_dump($request->post('address_id'));exit;

                //取出地址
                $addressId=$request->post('address_id');
                $address=Address::findOne(['id'=>$addressId,'user_id'=>$userId]);

                //取出配送方式
                $deliveryId=$request->post('delivery');
                $delivery=Delivery::findOne($deliveryId);

                //取出支付方式
                $payTypeId=$request->post('pay');
                $payType=PayType::findOne($payTypeId);


                //给order赋值
                $order->user_id = $userId;
                $order->name = $address->province;
                $order->city = $address->city;
                $order->county = $address->county;
                $order->address= $address->address;
                $order->mobile = $address->mobile;
                //快递id 配送方式  运费
                $order->delivery_id = $deliveryId;
                $order->delivery_name = $delivery->name;
                $order->delivery_price = $delivery->price;
                //支付方式信息
                $order->payment_id = $payTypeId;
                $order->payment_name = $payType->name;
                //订单总价
                $order->price = $shopPrice+$delivery->price;

                //订单状态
                $order->status=1;

                //订单号
                $order->trade_no = date("ymdHis").rand(1000,9999);

                $order->create_time=time();

                //var_dump($goods);exit;
                //保存数据
                if($order->save()){
                    //循环商品  进入商品详情表
                    foreach ($goods as $good){
                        //判断当前商品库存是否足够
                        //1、找出当前商品
                        $curGood = Goods::findOne($good->id);
                        //2、判断库存
                        if($cart[$good->id] > $curGood->stock){
                            //抛出异常
                            throw new Exception("库存不足");
                        }
                        $orderDetail = new OrderDetail();
                        $orderDetail->order_id = $order->id;
                        $orderDetail->goods_id = $good->id;
                        $orderDetail->amount = $cart[$good->id];
                        $orderDetail->goods_name = $good->name;
                        $orderDetail->logo = $good->logo;
                        $orderDetail->price = $good->shop_price;
                        $orderDetail->total_price = $good->shop_price*$orderDetail->amount;

                        //保存数据
                        if ($orderDetail->save()) {
                            //把当前商品的库存减去相应的出库量
                            $curGood->stock = $curGood->stock-$cart[$good->id];
                            $curGood->save(false);
                        }
                    }


                }

                //清空购物车订单商品
                Cart::deleteAll(['user_id'=>$userId]);


                //提交事务
                $transaction->commit();
                return Json::encode([
                    'status'=>1,
                    'msg'=>'订单提交成功！'
                ]);

            } catch(Exception $e) {
                //回滚
                $transaction->rollBack();


                throw $e;
            }

        }


        return $this->render('index',compact('addresss','deliverys','payTypes','cart','goods','shopPrice','shopNum'));
    }



}
