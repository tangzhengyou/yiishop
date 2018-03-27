<?php

namespace backend\controllers;

use backend\models\AuthItem;
use Behat\Gherkin\Loader\YamlFileLoader;
use yii\data\Pagination;
use yii\rbac\Permission;

class PermissionController extends \yii\web\Controller
{
    public function actionIndex()
    {
        /**
         * 权限列表
         */

        //1)创建auth对象
        $auth = \Yii::$app->authManager;
        //2)找到所有权限
        $pers = $auth->getPermissions();
        //获取所有数据
//        $query = Permission::find();
        //数据的总条数 每页显示多少条 当前页
//        $count = $pers->count();
//        //创建一个分页对象
//        $page = new Pagination([
//            //注：pagesize必须小于总数据条数
//            'pageSize' => 3,
//            'totalCount' => $count,
//        ]);
//        $pers=$pers->offset($page->offset)->limit($page->limit)->all();
        //var_dump($pers);exit;
        return $this->render('index',compact('pers','page'));
    }

    /**权限添加
     * @return string|\yii\web\Response
     */
    public function  actionAdd(){
      $model = new AuthItem();
      if($model->load(\Yii::$app->request->post()) && $model->validate()){

          //1)创建auth对象
          $auth = \Yii::$app->authManager;
          //2)创建权限
          $per=$auth->createPermission($model->name);

          //3)设置描述
          $per->description=$model->description;

          //4）权限入库
          if ($auth->add($per)) {
              //提示
              \Yii::$app->session->setFlash('success','权限'.$model->name.'添加成功');
              //刷新
              return $this->refresh();

          }
      }else{
          //var_dump($model->errors);


      }


      //显示视图
        return $this->render('add',compact('model'));

    }


    /**权限编辑
     * @param $name 权限名称
     * @return string|\yii\web\Response
     */
    public function  actionEdit($name){
        $model = AuthItem::findOne($name);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            //1)创建auth对象
            $auth = \Yii::$app->authManager;
            //2)得到权限
            $per=$auth->getPermission($name);

            //3)设置描述
            $per->description=$model->description;

            //4）权限入库
            if ($auth->update($model->name,$per)) {
                //提示
                \Yii::$app->session->setFlash('success','修改'.$model->name.'权限成功');
                //刷新
                return $this->refresh();

            }
        }else{
            //var_dump($model->errors);


        }


        //显示视图
        return $this->render('edit',compact('model'));

    }


    /**权限删除
     * @param $name  权限名称
     */
    public function actionDel($name){
        // 1)创建auth对象
        $auth = \Yii::$app->authManager;
        // 2）找到权限
        $per = $auth->getPermission($name);

        // 3）删除
        if ($auth->remove($per)) {
            //提示
            \Yii::$app->session->setFlash('success',"删除".$name."成功");
            return $this->redirect(['index']);
        }

    }

}
