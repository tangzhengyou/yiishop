<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/21
 * Time: 16:21
 */

namespace backend\models;


use yii\base\Model;

class LoginForm  extends Model
{
//1.设置属性
    public $username;
    public $password_hash;
    public $rememberMe=true;

    //2. 设置规则
    public function rules()
    {
        return [

            [['username','password_hash'],'required'],
            [['rememberMe'],'safe']

        ];
    }
    //3. 设置label

    public function attributeLabels()
    {
        return [
            'username'=>'username',
            'password'=>'password_hash'

        ];
    }

}