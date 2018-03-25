<?php

namespace backend\models;

use SebastianBergmann\Diff\TimeEfficientImplementationTest;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 商品名
 * @property int $sort 排序
 * @property int $brand_id 品牌ID
 * @property string $logo 商品logo
 * @property int $category_id 商品类别ID
 * @property string $market_price 市场价
 * @property string $shop_price 本店价
 * @property int $status 状态
 * @property string $stock 库存
 * @property string $sn 货号
 * @property int $create_time 商品创建时间
 */
class Goods extends \yii\db\ActiveRecord
{

    public $images;

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

                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
//                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
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
            [['name', 'brand_id', 'logo', 'category_id', 'market_price', 'shop_price','stock','images'] ,'required'],
            [['market_price','shop_price'],'number'],
            [['sort','status'],'integer'],
            [['sn'],'unique']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名',
            'sort' => '排序',
            'brand_id' => '品牌ID',
            'logo' => '商品logo',
            'category_id' => '商品类别ID',
            'market_price' => '市场价',
            'shop_price' => '本店价',
            'status' => '状态',
            'stock' => '库存',
            'sn' => '货号',
            'create_time' => '商品创建时间',
        ];
    }

}
