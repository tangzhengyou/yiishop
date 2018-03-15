<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property int $id
 * @property string $name 商品名
 * @property string $logo 商品logo
 * @property int $sort 排序
 * @property int $status 状态
 * @property string $intro 简介
 */
class Brand extends \yii\db\ActiveRecord
{
    public $img;
    public static $status=[0=>'禁用',1=>'激活'];


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','sort','status'], 'required'],
            [['sort', 'status'], 'integer'],
            [['intro'], 'safe'],
            [['img'],'image','skipOnEmpty' =>false,'extensions' => 'jpg,png,gif']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'img'=>'logo',
            'name' => '品牌名',
            'logo' => '商品logo',
            'sort' => '排序',
            'status' => '状态',
            'intro' => '简介',
        ];
    }
}
