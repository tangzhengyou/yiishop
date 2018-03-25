<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m180319_072138_create_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name'=> $this->string()->notNull()->comment('商品名'),
            'sort'=> $this->integer()->notNull()->comment('排序'),
            'brand_id'=> $this->integer()->notNull()->comment('品牌ID'),
            'logo'=> $this->string()->notNull()->comment('商品logo'),
            'category_id'=> $this->integer()->notNull()->comment('商品类别ID'),
            'market_price'=> $this->decimal()->notNull()->comment('市场价'),
            'shop_price'=> $this->decimal()->notNull()->comment('本店价'),
            'status'=> $this->smallInteger()->notNull()->defaultValue(1)->comment('状态'),
            'stock'=> $this->string()->notNull()->comment('库存'),
            'sn'=> $this->string()->notNull()->comment('货号'),
            'create_time'=> $this->integer()->notNull()->comment('商品创建时间'),

        ]);
        $this->createTable('goods_intro', [
            'id' => $this->primaryKey(),
            'goods_id'=> $this->integer()->notNull()->comment('商品ID'),
            'content'=> $this->text()->notNull()->comment('商品描述'),
        ]);

        $this->createTable('goods_gallery', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->notNull()->comment('商品ID'),
            'path'=>$this->string()->notNull()->comment('图片路径'),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods');
    }
}
