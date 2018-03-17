<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m180315_084418_create_brand_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=> $this->string()->notNull()->comment('商品名'),
            'logo'=> $this->string()->notNull()->comment('商品logo'),
            'sort'=> $this->integer()->notNull()->defaultValue(100)->comment('排序'),
            'status'=> $this->smallInteger()->notNull()->defaultValue(1)->comment('状态'),
            'intro'=> $this->text()->comment('简介')

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('brand');
    }
}
