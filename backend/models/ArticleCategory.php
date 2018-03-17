<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property int $id
 * @property string $name 类名
 * @property string $intro 简介
 * @property int $status 状态
 * @property int $sort 排序
 * @property int $is_help 是否是帮助类
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','sort','status','is_help'], 'required'],
            [['name'],'unique'],
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
            'name' => '类名',
            'intro' => '简介',
            'status' => '状态',
            'sort' => '排序',
            'is_help' => '是否是帮助类',
        ];
    }
}
