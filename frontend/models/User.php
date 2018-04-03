<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\debug\TimelineAsset;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $mobile 手机号
 * @property int $login_time 登录时间
 * @property int $login_ip 登录IP
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;
    public $rePassword;
    public $checkCode;
    public $captcha;
    public $rememberMe;


    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = ['username', 'password','checkCode','rememberMe'];
        $scenarios[self::SCENARIO_REGISTER] = ['username', 'email', 'password','rePassword','mobile','checkCode'];
        return $scenarios;
    }

    public function behaviors()
    {
        return [
            [

                'class' => TimestampBehavior::className(),
                'attributes' => [

                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password','rePassword','mobile','email'], 'required'],
            ['rePassword','compare','compareAttribute' => 'password','on' => self::SCENARIO_REGISTER],
            [['checkCode'],'captcha','captchaAction'=>'/user/code'],
            [['mobile'],'match','pattern'=>'/0?(13|14|15|17|18|19)[0-9]{9}/','message'=>'输入有效的手机号','on' => self::SCENARIO_REGISTER],
            [['captcha'],'validateCaptcha','on'=>self::SCENARIO_REGISTER],
            [['rememberMe'],'safe','on' => self::SCENARIO_LOGIN]

        ];
    }
    public function validateCaptcha($attribute, $params)
    {
        //1通过手机号取出之前发送出去的code
        $codeOld=\Yii::$app->session->get("tel_".$this->mobile);
         var_dump($this->captcha);
        //   exit($codeOld);

        //2.判断输入8code是否正确
        if ($this->captcha!=$codeOld){
            $this->addError($attribute, '短信验证码错误');
        }


    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'mobile' => '手机号',
            'login_time' => '登录时间',
            'login_ip' => '登录IP',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return self::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method. 自动登录
        return $this->auth_key;

    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->auth_key===$authKey;
    }
}
