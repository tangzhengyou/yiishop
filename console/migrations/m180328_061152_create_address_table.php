<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180328_061152_create_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer()->comment('用户ID'),
            'name'=>$this->string(20)->comment('用户名'),
            'province'=>$this->string(20)->comment('省份'),
            'city'=>$this->string(20)->comment('市'),
            'county'=>$this->string(20)->comment('区县'),
            'address'=>$this->string()->comment('详细地址'),
            'mobile'=>$this->string()->comment('手机号码'),
            'status'=>$this->smallInteger()->defaultValue(0)->comment('状态')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('address');
    }
}
