<?php

namespace backend\controllers;

use backend\models\AuthItem;
use Behat\Gherkin\Loader\YamlFileLoader;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

class RoleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        /**
         * 角色列表
         */
        //1)创建auth对象
        $auth = \Yii::$app->authManager;
        //2)找到所有权限
        $roles = $auth->getRoles();
        //var_dump($roles);exit;
        return $this->render('index',compact('roles'));
    }

    /**角色添加
     * @return string|\yii\web\Response
     */
    public function  actionAdd(){
      $model = new AuthItem();
        //创建auth对象
        $auth = \Yii::$app->authManager;
        //得到所有权限
        $pers = $auth->getPermissions();
        $persArr=ArrayHelper::map($pers,'name','description');

      if($model->load(\Yii::$app->request->post()) && $model->validate()){

//          //1)创建auth对象
//          $auth = \Yii::$app->authManager;
          //2)创建角色
          $role=$auth->createRole($model->name);

          //3)设置描述
          $role->description=$model->description;

          //4）角色入库
          if ($auth->add($role)) {
              //判定有没有添加权限
              if($model->permissions){
                  //给当前角色添加权限
                  foreach($model->permissions as $perNmae){
                      //通过权限得到权限对象
                      $per=$auth->getPermission($perNmae);
                      //给角色添加权限
                      $auth->addChild($role,$per);
                  }
              }


              //提示
              \Yii::$app->session->setFlash('success','角色'.$model->name.'添加成功');
              //刷新
//              return $this->refresh();
              //添加成功跳转
              return $this->redirect(['index']);


          }
      }else{
          //var_dump($model->errors);


      }


      //显示视图
        return $this->render('add',compact('model','persArr'));

    }


    /**角色编辑
     * @return string|\yii\web\Response
     */
    public function  actionEdit($name){
      $model = AuthItem::findOne($name);
        //创建auth对象
        $auth = \Yii::$app->authManager;
        //得到所有权限
        $pers = $auth->getPermissions();
        $persArr=ArrayHelper::map($pers,'name','description');
         //
        $rolePers=$auth->getPermissionsByRole($name);

        $model->permissions=array_keys($rolePers);


      if($model->load(\Yii::$app->request->post()) && $model->validate()){

//          //1)创建auth对象
//          $auth = \Yii::$app->authManager;
          //2)得到角色
          $role=$auth->getRole($model->name);

          //3)设置描述
          $role->description=$model->description;

          //4）更新角色
          if ($auth->update($model->name,$role)) {
              //删除当前角色对应的所有角色
              $auth->removeChildren($role);

              //判定有没有添加权限
              if($model->permissions){
                  //给当前角色添加权限
                  foreach($model->permissions as $perNmae){
                      //通过权限得到权限对象
                      $per=$auth->getPermission($perNmae);
                      //给角色添加权限
                      $auth->addChild($role,$per);
                  }
              }


              //提示
              \Yii::$app->session->setFlash('success','角色'.$model->name.'添加成功');
              //刷新
//              return $this->refresh();
              //添加成功跳转
              return $this->redirect(['index']);


          }
      }else{
          //var_dump($model->errors);


      }


      //显示视图
        return $this->render('edit',compact('model','persArr'));

    }





    /**角色删除
     * @param $name  权限名称
     */
    public function actionDel($name){
        // 1)创建auth对象
        $auth = \Yii::$app->authManager;
        // 2）找到角色
        $role = $auth->getRole($name);

        // 3）删除
        if ($auth->remove($role)) {
            //提示
            \Yii::$app->session->setFlash('success',"删除".$name."成功");
            return $this->redirect(['index']);
        }

    }

}
