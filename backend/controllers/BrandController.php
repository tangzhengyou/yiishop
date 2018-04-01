<?php

namespace backend\controllers;

use backend\models\Brand;
use crazyfd\qiniu\Qiniu;
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
        //提示
        \Yii::$app->session->setFlash('success','删除成功');

        return $this->redirect(['index']);
    }
}

    public function actionUpload(){
        switch (\Yii::$app->params['uploadType']){
            case "local":
                //本地上传
                //通过name值得到文件上传对象
                $fileObj =UploadedFile::getInstanceByName('file');
                //移动临时文件到WEB目录
                if ($fileObj!==null){
                    //拼路径
                    $filePath="images".".".time().".".$fileObj->extension;
                    //移动
                    if ($fileObj->saveAs($filePath,false)) {
                        // 正确时， 其中 attachment 指的是保存在数据库中的路径，url 是该图片在web可访问的地址
                        // {"code": 0, "url": "http://domain/图片地址", "attachment": "图片地址"}
                        //定义一个数组
                        $ok=[
                            'code'=>0,
                            'url'=>"/".$filePath,//预览地址
                            "attachment"=>$filePath//图片上传后地址
                        ];
                        //返回JSON数据
                        return json_encode($ok);
                    }
                }else{
                    // 错误时
                    //定义错误数组
                    $result=[
                        'code'=>1,
                        'msg'=>"error"
                    ];
                    return Json::encode($result);
                }
                break;
            case "qiniu":
                //七牛上传
                echo "qiniu";
                break;
        }
        exit;

    }

    /**文件上传到七牛
     * @return string
     */
    public function actionQiniuUpload(){
        //  var_dump($_FILES['file']);exit;
        $ak = 'eIG5N4TMAPyvdgjuozGwC8AOL5hGbiCCxnDONrIO';//应用名称
        $sk = 'gAItsLPcU9nNn5j7d3FAPkU7uwlfh9OJpbtpCXP3';//密钥
        $domain = 'http://p6e3ws62s.bkt.clouddn.com/';//地址
        $bucket = 'yiishop';//空间名称
        $zone = 'south_china';//区域

        //创建七牛云对象
        $qiniu = new Qiniu($ak, $sk,$domain, $bucket,$zone);
        $key = uniqid();//防止同一秒上传多张图片
        //拼路径  123541235132.gif
        $key =$key. strtolower(strrchr($_FILES['file']['name'], '.'));
        //利用七牛云对象上传文件
        $qiniu->uploadFile($_FILES['file']['tmp_name'],$key);
        $url = $qiniu->getLink($key);
        $result=[
            'code'=>0,
            'url'=>$url,//预览地址
            "attachment"=>$url//图片上传后地址
        ];
        //返回JSON数据
        return json_encode($result);
    }


}
