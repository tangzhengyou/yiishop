<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取所有数据
        $query = Brand::find();
        //数据的总条数 每页显示多少条 当前页
        $count = $query->count();
        //创建一个分页对象
        $page = new Pagination([
            //注：pagesize必须小于总数据条数
           'pageSize' => 3,
            'totalCount' => $count,
        ]);
        $brands=$query->offset($page->offset)->limit($page->limit)->all();

        return $this->render('index',compact('brands','page'));
    }

    /**
     * 添加方法
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        //
        $model = new Brand();
        //判断是否post方式提交
        if (\Yii::$app->request->isPost) {
            //数据绑定
            $model->load(\Yii::$app->request->post());
          //上传文件
//            $model->img=UploadedFile::getInstance($model,'img');
//            $imgPath="";
//            if($model->img!==null){
//                //拼路径
//                $imgPath = "images/".time().".".$model->img->extension;
//                //移动临时文件
//                $model->img->saveAs($imgPath,false);
//
//            }

            //后台验证
            if ($model->validate()) {
                //吧图片路径赋值给logo
//                $model->logo=$imgPath;
                //保存数据
                if ($model->save()) {
                    //登录成功提示
                    \Yii::$app->session->setFlash('success',"添加成功");
                    //登录成功跳转
                    return $this->redirect(['index']);
                }

            }else{
                //打印错误信息
//                var_dump($model->errors);exit;
            }
        }

        return $this->render('add',compact('model'));
    }

    /**
     * 编辑方法
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){
        //
        $model =Brand::findOne($id);
        //判断是否post方式提交
        if (\Yii::$app->request->isPost) {
            //数据绑定
            $model->load(\Yii::$app->request->post());
            //上传文件
            $model->img=UploadedFile::getInstance($model,'img');
            $imgPath="";
            if($model->img!==null){
                //拼路径
                $imgPath = "images/".time().".".$model->img->extension;
                //移动临时文件
                $model->img->saveAs($imgPath,false);

            }
            //后台验证
            if ($model->validate()) {
                //吧图片路径赋值给logo
                $model->logo=$imgPath;
                //保存数据
                if ($model->save()) {
                    //登录成功提示
                    \Yii::$app->session->setFlash('success',"编辑成功");
                    //登录成功跳转
                    return $this->redirect(['index']);
                }

            }else{
                //打印错误信息
//                var_dump($model->errors);exit;
            }
        }

        return $this->render('add',compact('model'));
    }

    /**
     * 删除方法
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDel($id){
    if (Brand::findOne($id)->delete()) {
        return $this->redirect(['index']);
    }
}

    /**文件上传到本地
     * @return string
     */
    public function actionUpload(){
        //通过name值得到上传对象
        $fileObj = UploadedFile::getInstanceByName('file');
//        var_dump($fileObj);exit;
        //移动临时文件
        if ($fileObj !==null) {
            //拼图片路径
            $filePath= "images/".time().".".$fileObj->extension;
//            var_dump($filePath);exit;
            if ($fileObj->saveAs($filePath,false)) {
                // 正确时， 其中 attachment 指的是保存在数据库中的路径，url 是该图片在web可访问的地址
//                {"code": 0, "url": "http://domain/图片地址", "attachment": "图片地址"}
                $correct =[
                    'code'=>0,
                    'url'=>"/".$filePath,
                    'attachment'=>$filePath
                ];
                return Json::encode($correct);

            }else{

                // 错误时
//                {"code": 1, "msg": "error"}
                $result = [
                    'code'=>1,
                    'msg'=>"error"
                ];
                return Json::encode($result);
            }
        }
    }

}
