<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property int $cate_id 分类id
 * @property string $title 标题
 * @property string $intro 简介
 * @property int $status 状态
 * @property int $sort 排序
 */
class Article extends \yii\db\ActiveRecord
{
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
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

            [['title', 'sort', 'status','cate_id'], 'required'],
            [['intro'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cate_id' => '分类id',
            'title' => '标题',
            'intro' => '简介',
            'status' => '状态',
            'sort' => '排序',
            'create_time'=>'创建时间',
            'update_time'=>'更新时间',


        ];
    }
    //找到对应的分类
    public function getCate(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'cate_id']);
    }
}
