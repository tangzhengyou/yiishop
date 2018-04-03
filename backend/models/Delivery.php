<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "delivery".
 *
 * @property int $id 快递id
 * @property string $name 快递名
 * @property string $price 价格
 * @property string $intro 简介
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['name'], 'string', 'max' => 30],
            [['intro'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '快递id',
            'name' => '快递名',
            'price' => '价格',
            'intro' => '简介',
        ];
    }
}
