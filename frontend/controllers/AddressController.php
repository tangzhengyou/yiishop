<?php

namespace frontend\controllers;

use frontend\models\Address;
use yii\helpers\Json;

class AddressController extends \yii\web\Controller
{
    public function actionIndex()
    {



        return $this->render('index');
    }
    public function actionAdd(){
        $request=\Yii::$app->request;
        if ($request->isPost){
            //创建一个模型对象
            $address = new Address();
            //绑定数据
            $address->load($request->post());
            //验证
            if($address->validate()){
                $address->user_id=\Yii::$app->user->id;
                //保存数据
                if ($address->save()) {
                    $result=[
                         'status'=>1,
                        'msg'=>'提交成功',

                    ];
                    return Json::encode($result);
                }


            }else{
                //提示错误
            }
        }
    }


}
