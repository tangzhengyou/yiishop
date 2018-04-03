<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pay_type".
 *
 * @property int $id 支付方式id
 * @property string $name 支付名称
 * @property string $intro 简介
 */
class PayType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100],
            [['intro'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '支付方式id',
            'name' => '支付名称',
            'intro' => '简介',
        ];
    }
}
