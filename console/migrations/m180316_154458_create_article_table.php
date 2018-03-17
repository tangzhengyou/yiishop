<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180316_154458_create_article_table extends Migration
{
    /**
     * {@inheritdoc}
     * 文章表
     */

    public function safeUp()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'cate_id'=> $this->integer()->comment('分类id'),
            'title'=> $this->string()->notNull()->comment('标题'),
            'intro'=> $this->text()->comment('简介'),
            'status'=> $this->smallInteger()->notNull()->defaultValue(1)->comment('状态'),
            'sort'=> $this->smallInteger()->notNull()->defaultValue(100)->comment('排序'),
            'create_time'=> $this->integer()->comment('创建时间'),
            'update_time'=> $this->integer()->comment('更新时间'),

        ]);
        //文章内容详情表
        $this->createTable('article_content', [
            'id'=> $this->primaryKey(),
            'detail'=> $this->text()->comment('内容详情'),
            'article_id'=> $this->integer()->comment('文章id')

            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article');
        $this->dropTable('article_category');
    }
}
