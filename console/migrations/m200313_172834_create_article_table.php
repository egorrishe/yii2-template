<?php

use yii\db\Migration;

/**
 * Class m200313_153322_create__article
 */
class m200313_172834_create_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=MyISAM';
        }

        $t = '{{%article}}';
        $this->createTable($t, [
            'id'      => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull(),

            'status'       => $this->tinyInteger()->notNull()->defaultValue(1),
            'created_date' => $this->integer()->notNull(),
            'updated_date' => $this->integer()->notNull(),

            'title'       => $this->string()->notNull(),
            'description' => $this->text(),
            'content'     => $this->text(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200313_153322_create__article cannot be reverted.\n";

        return false;
    }
    */
}
