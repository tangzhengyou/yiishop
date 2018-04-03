<?php

namespace frontend\controllers;

use backend\models\LoginForm;
use Codeception\Module\Yii1;
use frontend\components\ShopCart;
use frontend\models\Cart;
use frontend\models\User;
use Mrgoon\AliSms\AliSms;
use yii\helpers\Json;

class UserController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public $enableCsrfValidation=false;
    public function actions()
    {
        return [

            'code' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
                 'foreColor' =>0xFF4500
            ],
        ];
    }
    public function actionIndex()
    {

    }
    public function actionReg(){
        //判断是不是post提交
        $request=\Yii::$app->request;
        if($request->isPost){
//            exit('1111');
            //var_dump($request->isPost);exit;
            //创建模型对象
            $user = new User();
            //绑定数据
            $user->load($request->post());
            //后台验证

            if ($user->validate()) {
                //令牌
                $user->auth_key=\Yii::$app->security->generateRandomString();
                $user->password_hash=\Yii::$app->security->generatePasswordHash($user->password);
                //新增用户
                if ($user->save(false)) {
                    //跳转到登录页面
                    $result=[
                        'status'=>1,
                        'msg'=>'注册成功',
                        'data'=>"",
                    ];

                    return Json::encode($result);
                }
            }else{
                $result=[
                    'status'=>0,
                    'msg'=>'注册失败',
                    'data'=>$user->errors,
                ];
                return Json::encode($result);
                //var_dump($user->errors);exit(111);
            }

        }

        //显示视图
        return $this->render('reg');


    }

    /**
     * @param $mobile
     * @return int
     */
    public function actionSendSms($mobile){
        //1、生成验证码
        $code = rand(100000,999999);
        //2、把他验证码发送给mobile
        $config = [
            'access_key' => 'LTAINAvJ2Zx2zVCH',
            'access_secret' => 'Yp1c6TPrFiR4htQJGCuiPIiL5Q0Tnz',
            'sign_name' => '桦桦',
        ];
//
        //$aliSms = new Mrgoon\AliSms\AliSms();
        //创建一个短信发送的对象
        $aliSms = new AliSms();
        $response = $aliSms->sendSms($mobile, 'SMS_128641061', ['code'=>$code], $config);
//        var_dump($response);exit;
        if ($response->Message=="OK"){

            //3. 把code保存到Sretession中
            $session=\Yii::$app->session;
            $session->set("tel_".$mobile,$code);
//            return $code;
        }else{
            var_dump($response->Message);
        }
    }

   public function actionLogin(){

       //创建一个request对象
        $request=\Yii::$app->request;
        //判定是否post提交
        if ($request->isPost){
            $model = new User();


            //设置场景
            $model->setScenario(User::SCENARIO_LOGIN);
            //绑定数据
            $model->load($request->post());
           //后台验证

            //2、判定用户是否存在
            if ($model->validate()) {
                //1、找到用户
                $user=User::findOne(['username'=>$model->username]);
                //3、用户存在验证密码
                if($user && \Yii::$app->security->validatePassword($model->password,$user->password_hash)){
                    //4、用户登录
                    \Yii::$app->user->login($user,$model->rememberMe?3600*24*7:0);

                    //同步到数据库
                    (new ShopCart())->dbSyn()->flush()->save();


                    //同步cookie中的数据到数据库中去
//                    //1、取出cookie中的数据
//                    $cart = (new ShopCart())->get();
//
//                    //2、把数据同步到数据库中
//                    //判断当前用户购物车有没有商品
//                    //当前用户
//                    $userId =\Yii::$app->user->id;
//                    foreach ($cart as $goodId=>$num){
//                        $cartDB = Cart::findOne(['goods_id'=>$goodId,'user_id'=>$userId]);
//                        //判断
//                        if($cartDB){
//                            //有商品存在 执行 + 商品数量 $cart->num = $cart->num +$amount
//                            $cartDB->num += $num;
//
//
//                        }else{
//                            //没有商品存在  就新增商品
//                            //创建对象
//                            $cartDB = new Cart();
//                            //赋值
//                            $cartDB->goods_id=$goodId;
//                            $cartDB->num=$num;
//                            $cartDB->user_id=$userId;
//
//                        }
//                        //保存
//                        $cartDB->save();
//                    }

                    //3、清空本地cookie中的数据



                    $result=[
                        'status'=>1,
                        'msg'=>'登录成功',
                        'data'=>""

                    ];
                    return Json::encode($result);

                }else{
                    //用户名或密码错误
                    $result=[
                        'status'=>0,
                        'msg'=>'用户名或密码错误',
                        'data'=>$user->errors
                    ];
                    return Json::encode($result);
                }
            }

        }


        return $this->render('login');


   }
    public function actionLogout(){
        //
        if (\Yii::$app->user->logout()) {
            return $this->redirect(['/index/index']);
        }
    }


}
