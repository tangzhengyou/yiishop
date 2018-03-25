<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;
use Symfony\Component\Yaml\Yaml;
use yii\data\Pagination;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取所有数据
        $query = Admin::find();
        //数据的总条数 每页显示多少条 当前页
        $count = $query->count();
        //创建一个分页对象
        $page = new Pagination([
            //注：pagesize必须小于总数据条数
            'pageSize' => 3,
            'totalCount' => $count,
        ]);
        $admins=$query->offset($page->offset)->limit($page->limit)->all();

        return $this->render('index',compact('admins','page'));

    }

    public function actionLogin()
    {
        $model = new LoginForm();
        //创建一个request对象
        $request = \Yii::$app->request;
        //判断是否是post提交
        if ($request->isPost) {
            //绑定数据
            $model->load($request->post());
            //后台验证
            $admin = Admin::find()->where(['username'=>$model->username])->one();
            //判定用户是否存在
            if ($admin) {
                //\Yii::$app->security->validatePassword($model->password_hash,$admin->password_hash)
                //如果存在就验证密码  没有加密$model->password=$admin->password  $model->password_hash==$admin->password_hash
                if(\Yii::$app->security->validatePassword($model->password_hash,$admin->password_hash)){
                    //密码正确保存session
                    //通过user组件直接登录
                    \Yii::$app->user->login($admin,$model->rememberMe?3600*24*7:0);

                    //设置登录时间 和ip
                    $admin->login_at=time();
                    $admin->login_ip=\Yii::$app->request->userIP;
                    //更新用户
                    if ($admin->save()) {
                        \Yii::$app->session->setFlash("success","登录成功！");
                        return $this->redirect(['/admin/index']);
                    }

                }else{
                    //如果用户不存在 提示

                    $admin-> addError("password_hash","密码错误");
                }

            }else{
                $admin-> addError("username","用户名不存在或禁用");

            }


        }

        //显示login视图
        return $this->render('login', compact('model'));

    }

    public function actionAdd(){
        //创建一个模型对象
        $admin= new Admin();
        //原来的hash
//        $password = $admin->password_hash;
        //设置场景
        $admin->setScenario('add');

        //判断post提交
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //是post绑定数据
            $admin->load($request->post());
            //后台验证
            if ($admin->validate()) {

               // var_dump($admin->password_hash);exit;
                //给密码加密
                $admin->password_hash=\Yii::$app->security->generatePasswordHash($admin->password_hash);
                //var_dump($admin->password_hash);exit;
                //密码验证成功

                //设置令牌 随机的字符串
                $admin->auth_key=\Yii::$app->security->generateRandomString();
               //$admin->password_hash=$admin->password_hash?\Yii::$app->security->generatePasswordHash($admin->password_hash):$password;
                //验证成功保存数据
                if ($admin->save()) {
                    //添加成功提示
                    \Yii::$app->session->setFlash("success","添加成功");
                    return $this->redirect(['index']);
                }
            }else{
                //TODO：去掉就可以显示错误提示

            }
        }
//
//        //引入视图
        return $this->render('add',compact('admin'));
//
  }
    public function actionEdit($id){
        //创建一个模型对象
        $admin= Admin::findOne($id);
        //原来的hash
        $password = $admin->password_hash;
        //设置场景
        $admin->setScenario('edit');


        //判断post提交
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //是post绑定数据
            $admin->load($request->post());
            //后台验证
            if ($admin->validate()) {
                //给密码加密
                $admin->password_hash=\Yii::$app->security->generatePasswordHash($admin->password_hash);
                //密码验证成功

                //设置令牌 随机的字符串
                $admin->auth_key=\Yii::$app->security->generateRandomString();

                $admin->password_hash=$admin->password_hash?\Yii::$app->security->generatePasswordHash($admin->password_hash):$password;

                //验证成功保存数据
                if ($admin->save()) {
                    //添加成功提示
                    \Yii::$app->session->setFlash("success","修改成功");
                    return $this->redirect(['index']);
                }
            }else{
                //TODO：去掉就可以显示错误提示

            }
        }
      $admin->password_hash=null;
//        //引入视图
        return $this->render('add',compact('admin'));
//
  }
  public function actionLogout(){
        //
      if (\Yii::$app->user->logout()) {
          return $this->redirect(['/admin/login']);
      }
  }


  public function actionTest(){


      var_dump(\Yii::$app->security->validatePassword('1','$2y$13$L/IVayvohn9J67CmXGQ34.D4bMBJPnrFneaOQnTnetk.QCaEn2y6q'));

  }

  public function actionAdminRole($roleName,$id){
      //实例化组件对象
      $auth = \Yii::$app->authManager;
      //通过角色名找出角色对象
      $role = $auth->getRole($roleName);
      //把用户指派给角色
      $auth->assign($role,$id);
  }
  public function actionCheck(){
      \Yii::$app->user->can('goods/add');
  }

}
