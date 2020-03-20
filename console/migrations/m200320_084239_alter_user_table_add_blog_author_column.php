<?php

use yii\db\Migration;

/**
 * Class m200320_084239_alter_user_table_add_author_column
 */
class m200320_084239_alter_user_table_add_blog_author_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'user',
            'blog_author',
            $this->tinyInteger()
                ->notNull()
                ->defaultValue(0)
                ->comment('E.g. is user allowed to create Article')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'blog_author');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200320_084239_alter_user_table_add_author_column cannot be reverted.\n";

        return false;
    }
    */
}
