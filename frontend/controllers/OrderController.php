<?php

namespace frontend\controllers;

use backend\models\Delivery;
use backend\models\Goods;
use backend\models\Order;
use backend\models\OrderDetail;
use backend\models\PayType;
use Endroid\QrCode\QrCode;
use frontend\models\Address;
use frontend\models\Cart;
use function Sodium\compare;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use EasyWeChat\Foundation\Application;
use yii\helpers\Url;

class OrderController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
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
                    'msg'=>'订单提交成功!',
                    'id'=>$order->id,
                ]);

            } catch(Exception $e) {
                //回滚
                $transaction->rollBack();
               return Json::encode([
                   'status'=>0,
                   'msg'=>$e->getMessage(),
               ]);
            }

        }




        return $this->render('index',compact('addresss','deliverys','payTypes','cart','goods','shopPrice','shopNum'));
    }

public function actionOk($id){
    $order = Order::findOne($id);
    return $this->render("ok",compact("order"));
}

  public function actionWx($id){
        //查出当前订单
      $order = Order::findOne($id);
//配置
      $options = \Yii::$app->params['wx'];
      //var_dump($options);exit;
      //创建操作微信的对象
      $app = new Application($options);
      //通过$app得到支付对象
      $payment = $app->payment;

      $attributes = [
          'trade_type'       => 'NATIVE', // JSAPI，NATIVE，APP...
          'body'             => 'iPad mini 16G 白色',
          'detail'           => 'detail iPad mini 16G 白色',
          'out_trade_no'     => $order->trade_no,
          'total_fee'        =>1, // 单位：分
          'notify_url'       =>Url::to(['order/notify'],true), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
          //'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
          // ...
      ];
      //通过订单信息生成订单
      $order = new \EasyWeChat\Payment\Order($attributes);
      //订单详情信息 统一下单
      $result = $payment->prepare($order);
      //var_dump($result);exit;

      if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){

          $qrCode = new QrCode($result->code_url);

          header('Content-Type: '.$qrCode->getContentType());

          echo $qrCode->writeString();

      }else{
          var_dump($result);exit;
      }
  }

    public function actionNotify(){
        $options = \Yii::$app->params['wx'];
        //var_dump($options);exit;
        //创建操作微信的对象
        $app = new Application($options);
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            //$order = 查询订单($notify->out_trade_no);
            $order=Order::findOne(["trade_no"=>$notify->out_trade_no]);
            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->status!=1) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                //$order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 2; // 1.等待的支付  2.已支付
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        return $response;
    }




}
