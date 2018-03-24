<?php
namespace backend\controllers;

use backend\models\Admin;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(){

        //1.取出数据
        $model=Admin::find()->all();
        return $this->render('login',compact('model'));
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        //创建一个request对象
        $request = Yii::$app->request;
        //判断是否是post提交
        if ($request->isPost) {
            //绑定数据
            $model->load(Yii::$app->request->post());
            //后天验证
            $admin = Admin::find()->where(['username'=>$model->username])->one();
            //判定用户是否存在
            if ($admin) {
                //如果存在就验证密码
                if($model->password=$admin->password){
                    //密码正确保存session
                    //通过user组件直接登录
                    \Yii::$app->session->setFlash("success","登录成功！");
                    return $this->redirect(['admin/index']);

                }else{
                    //如果用户不存在 提示

                    $admin-> addError("password","密码错误");
                }

                }else{
                    $admin-> addError("username","用户名不存在");

            }


        }


        return $this->render('login', ['model' => $model]);
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            return $this->goBack();
//        } else {
//            $model->password = '';
//
//            return $this->render('login', [
//                'model' => $model,
//            ]);
//        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
