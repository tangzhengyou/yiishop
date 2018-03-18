<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m180318_082817_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),

            'name'=> $this->string()->notNull()->comment('商品类名'),
            'intro'=> $this->string()->notNull()->comment('商品类名'),
            'left'=> $this->integer()->notNull()->comment('左值'),
            'right'=> $this->integer()->notNull()->comment('右值'),
            'depth'=> $this->integer()->notNull()->comment('深度'),
            'parent_id'=> $this->integer()->comment('父类id'),
            'tree'=> $this->integer()->notNull()->comment('分类组')



        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }
}
